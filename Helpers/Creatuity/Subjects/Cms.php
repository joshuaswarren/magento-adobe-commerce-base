<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Plugin\CmsUrlRewritePlugin;
use Creatuity\Base\Helpers\Creatuity\Subjects\Cms\ContentProcessorInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\Data\PageInterfaceFactory;
use Magento\Cms\Api\Data\BlockInterfaceFactory;
use Magento\Cms\Model\Page;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory as PageCollectionFactory;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory as BlockCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2021 Joshua Warren (https://warrenappliedlabs.com)
 */
class Cms extends SubjectAbstract implements SubjectForModuleInterface
{
    private string $moduleName = '';

    private bool $isContentProcessingActive = true;

    private PageInterfaceFactory $cmsPageFactory;
    private BlockInterfaceFactory $cmsBlockFactory;
    private PageCollectionFactory $cmsPageCollectionFactory;
    private BlockCollectionFactory $cmsBlockCollectionFactory;

    /** @var ContentProcessorInterface[] */
    private array $contentProcessors = [];

    public function __construct(
        Creatuity $creatuity,
        PageInterfaceFactory $cmsPageFactory,
        BlockInterfaceFactory $cmsBlockFactory,
        PageCollectionFactory $cmsPageCollectionFactory,
        BlockCollectionFactory $cmsBlockCollectionFactory,
        array $contentProcessors
    ) {
        parent::__construct($creatuity);

        $this->cmsPageFactory = $cmsPageFactory;
        $this->cmsBlockFactory = $cmsBlockFactory;
        $this->cmsPageCollectionFactory = $cmsPageCollectionFactory;
        $this->cmsBlockCollectionFactory = $cmsBlockCollectionFactory;
        $this->contentProcessors = $contentProcessors;
    }

    public function blockSave(string $identifier, array $params = [], bool $mustExists = false): BlockInterface
    {
        $contentHtml = $this->creatuity()->resources($this->moduleName)->fileRead($this->blockPathPattern() . "{$identifier}.html");
        $contentHtml = $this->processContent($contentHtml);

        return $this->blockSaveContent($identifier, $params, $mustExists, $contentHtml);
    }

    public function blockSaveContent(string $identifier, array $params, bool $mustExists, string $content): BlockInterface
    {
        $config = $this->creatuity()->resources($this->moduleName)->jsonRead($this->blockPathPattern() . "{$identifier}.json", $params, [
            'identifier' => $identifier,
            'is_active' => Page::STATUS_ENABLED,
            'from_store' => null,

            # If you're going to change below line, please let mostrowski/mpietrzyk know first, as it seems that some kind of black magic is involved in that line ;p
            'stores' => [0]
        ]);

        $block = $this->blockInstance($config['identifier'], !empty($config['from_store']) ? $config['from_store'] : null, $mustExists);
        $blockInstanceHasExisted = (bool)$block->getId();

        $newData = array_replace_recursive($block->getData(), $config, ['content' => $content]);

        if (!isset($newData['title'])) {
            $newData['title'] = $this->titleFromIdentifier($newData['identifier']);
        }

        $block->setTitle($newData['title'])
            ->setIdentifier($newData['identifier'])
            ->setIsActive($newData['is_active'])
            ->setStores($newData['stores'])
            ->setContent($newData['content'])
            ->save();

        $this->creatuity()->report()->printSuccess(
            sprintf('Block Save: "%s" - successfully %s block under identifier "%s" ',
                $identifier, $blockInstanceHasExisted ? 'updated' : 'created', $newData['identifier']
            ));

        return $block;
    }

    public function pageSave(string $identifier, array $params = [], bool $mustExists = false): PageInterface
    {
        return CmsUrlRewritePlugin::runWithEnabled(function () use ($identifier, $params, $mustExists) {
            $contentHtml = $this->creatuity()->resources($this->moduleName)->fileRead($this->pagePathPattern() . "{$identifier}.html");
            $contentHtml = $this->processContent($contentHtml);

            return $this->pageSaveContent($identifier, $params, $mustExists, $contentHtml);
        });
    }

    public function pageSaveContent(string $identifier, array $params, bool $mustExists, string $content): PageInterface
    {
        $layoutUpdateXml = $this->creatuity()->resources($this->moduleName)->fileRead($this->pagePathPattern() . "{$identifier}.layout.xml", false);
        $config = $this->creatuity()->resources($this->moduleName)->jsonRead($this->pagePathPattern() . "{$identifier}.json", $params, [
            'identifier' => $identifier,
            'is_active' => Page::STATUS_ENABLED,
            'from_store' => null,
            'page_layout' => '1column',
            'layout_update_xml' => $layoutUpdateXml,

            # If you're going to change below line, please let mostrowski/mpietrzyk know first, as it seems that some kind of black magic is involved in that line ;p
            'stores' => [0],
        ]);

        $page = $this->pageInstance($config['identifier'], $config['from_store'], $mustExists);
        $pageInstanceHasExisted = (bool)$page->getId();

        $newData = array_replace_recursive($page->getData(), $config, ['content' => $content]);

        if (!isset($newData['title'])) {
            $newData['title'] = $this->titleFromIdentifier($newData['identifier']);
        }

        if (isset($newData['layout_update_selected'])) {
            $page->setData('layout_update_selected', $newData['layout_update_selected']);
        }

        $page->setTitle($newData['title'])
            ->setIdentifier($newData['identifier'])
            ->setIsActive($newData['is_active'])
            ->setStores($newData['stores'])
            ->setPageLayout($newData['page_layout'])
            ->setLayoutUpdateXml($newData['layout_update_xml'])
            ->setContent($newData['content'])
            ->setContentHeading($newData['content_heading'])
            ->save();

        $this->creatuity()->report()->printSuccess(
            sprintf('Page Save: "%s" - successfully %s page under identifier "%s" ',
                $identifier, $pageInstanceHasExisted ? 'updated' : 'created', $newData['identifier']
            ));

        return $page;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function blockDelete(string $identifier, ?int $store = null, bool $mustExists = false): void
    {
        try {
            $block = $this->blockInstance($identifier, $store, $mustExists);
            if ($block->getId()) {
                $block->delete();
                $this->creatuity()->report()->printSuccess("Successfully deleted block '{$identifier}' ");
            }
        } catch (NoSuchEntityException $e) {
            if ($mustExists) {
                throw $e;
            }
        }
    }

    /**
     * @throws NoSuchEntityException
     */
    public function pageDelete(string $identifier, ?int $store = null, bool $mustExists = false): void
    {
        try {
            CmsUrlRewritePlugin::runWithEnabled(function () use ($identifier, $store, $mustExists) {
                $page = $this->pageInstance($identifier, $store, $mustExists);
                if ($page->getId()) {
                    $page->delete();
                    $this->creatuity()->report()->printSuccess("Successfully deleted page '{$identifier}' ");
                }
            });
        } catch (NoSuchEntityException $e) {
            if ($mustExists) {
                throw $e;
            }
        }
    }

    /**
     * @throws NoSuchEntityException
     */
    public function pageInstance(string $identifier, ?int $store = null, bool $mustExists = false): PageInterface
    {
        $pageCollection = $this->cmsPageCollectionFactory->create()
            ->addFieldToFilter('identifier', $identifier);

        if (!is_null($store)) {
            $pageCollection->addStoreFilter($store, false);
        }

        if ($pageCollection->getFirstItem()->getId()) {
            return $pageCollection->getFirstItem();
        } elseif ($mustExists) {
            throw new NoSuchEntityException(__("CMS Page with identifier '$identifier' does not exist." ));
        }

        return $this->cmsPageFactory->create()->setIdentifier($identifier);
    }

    /**
     * @return BlockInterface
     */
    public function blockInstance(string $identifier, ?int $store = null, bool $mustExists = false): BlockInterface
    {
        $blockCollection = $this->cmsBlockCollectionFactory->create()
            ->addFieldToFilter('identifier', $identifier);

        if (!is_null($store)) {
            $blockCollection->addStoreFilter($store, false);
        }

        if ($blockCollection->getFirstItem()->getId()) {
            return $blockCollection->getFirstItem();
        } elseif ($mustExists) {
            throw new NoSuchEntityException(__("CMS Block with identifier '$identifier' does not exist." ));
        }

        return $this->cmsBlockFactory->create()
            ->setIdentifier($identifier);
    }

    public function disableContentProcessing(): self
    {
        $this->isContentProcessingActive = false;

        return $this;
    }

    private function processContent(string $content): string
    {
        if (!$this->isContentProcessingActive) {
            /**
             * Content processing is being enabled now.
             * We want to have it disabled only for current function call.
             */
            $this->isContentProcessingActive = true;

            return $content;
        }

        foreach ($this->contentProcessors as $contentProcessor) {
            $content = $contentProcessor->process($content);
        }

        return $content;
    }

    /**
     * Replaces 'this-is-a-title-case-string' with 'This is a Title Case String'
     */
    protected function titleFromIdentifier(string $identifier): string
    {
        $words = explode('-', $identifier);
        //https://en.wikipedia.org/wiki/Title_case
        $omit = [
            'of', 'a', 'the', 'and', 'an', 'or', 'nor',
            'but', 'is', 'if', 'then', 'else', 'when',
            'at', 'from', 'by', 'on', 'off', 'for',
            'in', 'out', 'over', 'to', 'into', 'with'
        ];

        foreach ($words as $key => $word) {
            if ($key == 0 || !in_array($word, $omit)) {
                $words[$key] = ucfirst($word);
            }
        }

        return implode(' ', $words);
    }

    protected function pagePathPattern(): string
    {
        return '';
    }

    protected function blockPathPattern(): string
    {
        return '';
    }

    /**
     * @param string $moduleName
     * @return $this
     */
    public function forModule($moduleName)
    {
        $this->moduleName = $moduleName;
        return $this;
    }
}
