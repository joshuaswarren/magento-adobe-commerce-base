<?php

namespace Creatuity\Base\Setup;

use Creatuity\Base\Setup\Abstracts\AbstractInstallUpgradeSchemaData;
use Creatuity\Base\Setup\Abstracts\AbstractUpgradeDataContext;
use Creatuity\Base\Setup\Type\CatalogInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
abstract class AbstractType
{
    /**
     * @var AbstractInstallUpgradeSchemaData
     */
    protected $parent;
    /**
     * @var AbstractUpgradeDataContext
     */
    protected $context;
    /**
     * @var string
     */
    protected $entityType;

    public function __construct(TypeInterface $parent, AbstractUpgradeDataContext $context)
    {
        $this->parent = $parent;
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * @return mixed
     */
    protected function getParent()
    {
        return $this->parent;
    }

    /**
     * @return AbstractUpgradeDataContext
     */
    protected function getContext()
    {
        return $this->context;
    }

    /**
     * @return HasType
     */
    protected function type()
    {
        return $this->parent;
    }

    /**
     * @return CatalogInterface
     */
    protected function catalog()
    {
        return $this->type()->catalog();
    }
}
