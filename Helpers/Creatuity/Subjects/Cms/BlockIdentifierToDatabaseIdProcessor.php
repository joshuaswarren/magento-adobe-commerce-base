<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects\Cms;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class BlockIdentifierToDatabaseIdProcessor implements ContentProcessorInterface
{
    private BlockRepositoryInterface $blockRepository;

    public function __construct(
        BlockRepositoryInterface $blockRepository
    ) {
        $this->blockRepository = $blockRepository;
    }

    /**
     * @throws InputException|LocalizedException
     */
    public function process(string $content): string
    {
        $matches = [];

        preg_match_all('/block_id *= *["\'](.*?)["\']\s/', $content, $matches);

        foreach ($matches[0] as $key => $text) {
            $identifier = $matches[1][$key];
            $id = $this->getBlockIdFromIdentifier($identifier);

            $content = str_replace($text, 'block_id="' . $id . '"', $content);
        }

        return $content;
    }

    /**
     * @throws LocalizedException
     * @throws InputException
     */
    private function getBlockIdFromIdentifier(string $identifier): ?int
    {
        try {
            $block = $this->blockRepository->getById($identifier);
        } catch (NoSuchEntityException $exception) {
            throw new InputException(
                __("CMS Block Identifier $identifier is not existent in Database")
            );
        }

        return $block->getId();
    }
}
