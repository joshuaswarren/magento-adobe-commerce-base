<?php

namespace Creatuity\Base\Setup\Abstracts;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Subjects\Catalog;
use Creatuity\Base\Helpers\Creatuity\Subjects\Cms;
use Creatuity\Base\Helpers\Creatuity\Subjects\Database;
use Creatuity\Base\Helpers\Creatuity\Subjects\Indexer;
use Creatuity\Base\Helpers\Creatuity\Subjects\Report;
use Creatuity\Base\Helpers\Creatuity\Subjects\Resources;
use Creatuity\Base\Helpers\Creatuity\Subjects\Setting;
use Creatuity\Base\Helpers\Creatuity\Subjects\Store;
use Creatuity\Base\Helpers\Creatuity\Subjects\Theme;
use Creatuity\Base\Model\CsvParser\CsvParserInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class BackwardCompatibility
{
    /**
     * @var Creatuity
     */
    private $creatuity;

    public function __construct(AbstractUpgradeDataContext $context)
    {
        $this->creatuity = $context->getCreatuity()->forModule($context->getModuleNameResolver()->byObject($this));
    }


    /**
     * @return Cms
     * @deprecated use $this->creatuity()->cms() instead
     */
    public function cms()
    {
        return $this->creatuity->cms();
    }

    /**
     * @return Resources
     * @deprecated use $this->creatuity()->resources() instead
     */
    public function resources()
    {
        return $this->creatuity->resources();
    }

    /**
     * @return Report
     * @deprecated use $this->creatuity()->report() instead
     */
    public function report()
    {
        return $this->creatuity->report();
    }

    /**
     * @return Database
     * @deprecated use $this->creatuity()->database() instead
     */
    public function database()
    {
        return $this->creatuity->database();
    }

    /**
     * @return Catalog
     * @deprecated use $this->creatuity()->catalog() instead
     */
    public function catalog()
    {
        return $this->creatuity->catalog();
    }

    /**
     * @return CsvParserInterface
     * @deprecated use $this->creatuity()->csv() instead
     */
    public function csv()
    {
        return $this->creatuity->csv();
    }

    /**
     * @return Theme
     * @deprecated use $this->creatuity()->theme() instead
     */
    public function theme()
    {
        return $this->creatuity->theme();
    }

    /**
     * @return Indexer
     * @deprecated use $this->creatuity()->indexer() instead
     */
    public function indexer()
    {
        return $this->creatuity->indexer();
    }

    /**
     * @return Store
     * @deprecated use $this->creatuity()->store() instead
     */
    public function store()
    {
        return $this->creatuity->store();
    }

    /**
     * @return Setting
     * @deprecated use $this->creatuity()->setting() instead
     */
    public function settings($scopeType = 'default', $scope = 0, $shared = true)
    {
        return $this->creatuity->setting($scope, $scopeType);
    }

    /**
     * @return Setting
     * @deprecated use $this->creatuity()->setting() instead
     */
    public function settingsForStore($storeId)
    {
        return $this->creatuity->setting(
            $this->creatuity->store()->storeViewModel($storeId)->getId(), ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return Setting
     * @deprecated use $this->creatuity()->setting() instead
     */
    public function settingsForStoreGroup($storeGroupId)
    {
        return $this->creatuity->setting(
            $this->creatuity->store()->storeGroupModel($storeGroupId)->getId(), ScopeInterface::SCOPE_GROUP
        );
    }

    /**
     * @return Setting
     * @deprecated use $this->creatuity()->setting() instead
     */
    public function settingsForWebsite($websiteId)
    {
        return $this->creatuity->setting(
            $this->creatuity->store()->websiteModel($websiteId)->getId(), ScopeInterface::SCOPE_WEBSITES
        );
    }
}
