<?php
namespace Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\Required;

use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Catalog\Attribute\Entity\RequiredInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Scope extends AbstractType implements ScopeInterface
{

    /**
     * The attribute will be saved for all products at global level
     *
     * @return RequiredInterface
     */
    public function scopeGlobal()
    {
        return $this->_setScope( Attribute::SCOPE_GLOBAL );
    }

    /**
     * The attribute will be saved for all products at website level
     *
     * @return RequiredInterface
     */
    public function scopeWebsite()
    {
        return $this->_setScope( Attribute::SCOPE_WEBSITE );
    }

    /**
     * The attribute will be saved for all products at store view level
     *
     * @return RequiredInterface
     */
    public function scopeStoreView()
    {
        return $this->_setScope( Attribute::SCOPE_STORE );
    }

    /**
     *
     * @param int $scope
     * @return RequiredInterface
     */
    protected function _setScope( $scope )
    {
        $this->getParent()->getParent()->setScope( $scope );
        return $this->getParent();
    }
}
