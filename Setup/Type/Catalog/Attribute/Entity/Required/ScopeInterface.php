<?php
namespace Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Required;

use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\RequiredInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface ScopeInterface
{

    /**
     * The attribute will be saved for all products at global level
     *
     * @return RequiredInterface
     */
    public function scopeGlobal();

    /**
     * The attribute will be saved for all products at website level
     *
     * @return RequiredInterface
     */
    public function scopeWebsite();

    /**
     * The attribute will be saved for all products at store view level
     *
     * @return RequiredInterface
     */
    public function scopeStoreView();
}
