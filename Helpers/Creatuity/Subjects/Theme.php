<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\Store;
use Magento\Theme\Model\Config;
use Magento\Theme\Model\ResourceModel\Theme\CollectionFactory;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Theme extends SubjectAbstract
{
    private CollectionFactory $collectionFactory;
    private Config $themeConfig;

    public function __construct(
        CollectionFactory $collectionFactory,
        Config $themeConfig,
        Creatuity $creatuity
    ) {
        parent::__construct($creatuity);

        $this->collectionFactory = $collectionFactory;
        $this->themeConfig = $themeConfig;
    }

    public function assignThemeToDefaultStore(string $themeCode): void
    {
        $this->assignTheme(
            $themeCode,
            [ Store::DEFAULT_STORE_ID ],
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );

        $this->creatuity()->report()->printSuccess("'$themeCode' theme has been assigned to default store'");
    }

    public function assignThemeToStore(string $themeCode, array $stores, string $scope): void
    {
        $this->assignTheme($themeCode, $stores, $scope);

        $this->creatuity()->report()->printSuccess("'$themeCode' theme has been activated'");
    }

    protected function assignTheme($themeCode, array $stores, string $scope): void
    {
        foreach ($this->collectionFactory->create()->loadRegisteredThemes() as $theme) {
            if ($theme->getCode() === $themeCode) {
                $this->themeConfig->assignToStore($theme, $stores, $scope);
                return;
            }
        }

        $this->creatuity()->report()->printError("'$themeCode' has not been found. Verify if code is existent.'");
    }
}
