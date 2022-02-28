<?php

namespace Creatuity\Base\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Creatuity\Base\Setup\Abstracts\AbstractInstallUpgradeSchemaData;
use Creatuity\Base\Setup\Abstracts\AbstractUpgradeDataContext;

/**
 * @category Creatuity
 * @package phw
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 * @license https://warrenappliedlabs.com/license
 */
abstract class AbstractInstallSchema extends AbstractInstallUpgradeSchemaData implements InstallSchemaInterface
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
                "You probably wanted upgrade schema instead? " . PHP_EOL
                . "Remember Install Schema is just a kind of summary of many upgrade schemas." . PHP_EOL
                . "Do you have Upgrade Schema next to your Install Schema? If not, you shouldn't use that class." . PHP_EOL
                . "Install schemas fits only for standalone plugins. They don not fit to M2 projects, semantically." . PHP_EOL
            );
        }
    }
}