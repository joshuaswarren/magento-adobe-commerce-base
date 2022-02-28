<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\Store;
use Magento\Theme\Model\Config;
use Magento\Theme\Model\ResourceModel\Theme\CollectionFactory;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class Theme extends SubjectAbstract
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Config
     */
    protected $themeConfig;

    public function __construct(
        CollectionFactory $collectionFactory,
        Config $themeConfig,
        Creatuity $creatuity
    ) {
        parent::__construct($creatuity);

        $this->collectionFactory = $collectionFactory;
        $this->themeConfig = $themeConfig;
    }

    public function assignThemeToDefaultStore($themeCode)
    {
        $this->assignTheme(
            $themeCode,
            [ Store::DEFAULT_STORE_ID ],
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );

        $this->creatuity()->report()->printSuccess("'$themeCode' theme has been assigned to default store'");
    }

    public function assignThemeToStore($themeCode, array $stores, $scope)
    {
        $this->assignTheme($themeCode, $stores, $scope);

        $this->creatuity()->report()->printSuccess("'$themeCode' theme has been activated'");
    }

    protected function assignTheme($themeCode, array $stores, $scope)
    {
        foreach ($this->collectionFactory->create()->loadRegisteredThemes() as $theme) {
            if ($theme->getCode() === $themeCode) {
                $this->themeConfig->assignToStore($theme, $stores, $scope);
            }
        }
    }
}