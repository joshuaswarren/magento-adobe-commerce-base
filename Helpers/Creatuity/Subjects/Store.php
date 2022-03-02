<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Exception;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Group;
use Magento\Store\Model\GroupFactory as StoreFactory;
use Magento\Store\Model\Store as StoreViewModel;
use Magento\Store\Model\StoreFactory as StoreViewFactory;
use Magento\Store\Model\Website;
use Magento\Store\Model\WebsiteFactory;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Store extends SubjectAbstract
{
    private const DEFAULT_ROOT_CATEGORY = 2;

    private StoreViewFactory $storeViewFactory;
    private StoreFactory $storeFactory;
    private WebsiteFactory $websiteFactory;

    public function __construct(
        StoreViewFactory $storeViewFactory,
        StoreFactory $storeFactory,
        WebsiteFactory $websiteFactory,
        Creatuity $creatuity
    ) {
        parent::__construct($creatuity);

        $this->storeViewFactory = $storeViewFactory;
        $this->storeFactory = $storeFactory;
        $this->websiteFactory = $websiteFactory;
    }

    /**
     * @param string|int $codeOrNameOrId
     * @param bool $mustExists
     * @return Website
     * @throws Exception
     */
    public function websiteModel($codeOrNameOrId, bool $mustExists = true): Website
    {
        $website = $this->websiteFactory->create()->load($codeOrNameOrId, is_numeric($codeOrNameOrId) ? null : 'code');
        if (!$this->isExists($website)) {
            $website = $this->websiteFactory->create()->load($codeOrNameOrId, 'name');
        }

        if ($mustExists && !$this->isExists($website)) {
            throw new Exception("I couldn't find website given by '{$codeOrNameOrId}'");
        }

        return $website;
    }

    /**
     * @param string|int $codeOrNameOrId
     * @param bool $mustExists
     * @return Group
     * @throws Exception
     */
    public function storeGroupModel($codeOrNameOrId, bool $mustExists = true): Group
    {
        $storeGroup = $this->storeFactory->create()->load($codeOrNameOrId, is_numeric($codeOrNameOrId) ? null : 'code');
        if ( !$this->isExists($storeGroup) ) {
            $storeGroup = $this->storeFactory->create()->load($codeOrNameOrId, 'name');
        }

        if ($mustExists && !$this->isExists($storeGroup)) {
            throw new Exception("I couldn't find store group given by '{$codeOrNameOrId}'");
        }

        return $storeGroup;
    }

    /**
     * @param string|int $codeOrNameOrId
     * @param bool $mustExists
     * @return StoreViewModel
     * @throws Exception
     */
    public function storeViewModel($codeOrNameOrId, bool $mustExists = true): StoreViewModel
    {
        $storeView = $this->storeViewFactory->create()->load($codeOrNameOrId, is_numeric($codeOrNameOrId) ? null : 'code');
        if (!$this->isExists($storeView)) {
            $storeView = $this->storeViewFactory->create()->load($codeOrNameOrId, 'name');
        }

        if ($mustExists && !$this->isExists($storeView)) {
            throw new Exception("I couldn't find store view given by '{$codeOrNameOrId}'");
        }

        return $storeView;
    }

    private function isExists(AbstractModel $model): bool
    {
        return is_numeric($model->getId());
    }
}
