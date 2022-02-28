<?php

namespace Creatuity\Base\Setup;

use Creatuity\Base\Setup\Abstracts\AbstractUpgradeDataContext;
use Creatuity\Base\Setup\Abstracts\AbstractInstallUpgradeSchemaData;
use Magento\Framework\Setup\InstallDataInterface;

/**
 * @category Creatuity
 * @package phw
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 * @license https://warrenappliedlabs.com/license
 */
abstract class AbstractInstallData extends AbstractInstallUpgradeSchemaData implements InstallDataInterface
{
    /**
     * @var bool
     */
    protected $allowInstallScripts = false;

    public function __construct(AbstractUpgradeDataContext $context)
    {
        parent::__construct($context);

        if (!$this->allowInstallScripts) {
            throw new \Exception(
                "You probably wanted upgrade script instead? " . PHP_EOL
                . "Remember Install Script is just a kind of summary of many upgrade scripts." . PHP_EOL
                . "Do you have Upgrade Script next to your Install Script? If not, you shouldn't use that class." . PHP_EOL
                . "Install scripts fits only for standalone plugins. They don not fit to M2 projects, semantically." . PHP_EOL
            );
        }
    }


}