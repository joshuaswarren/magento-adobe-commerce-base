<?php
namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional;


use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional;

class UsedInForms
    extends AbstractType
    implements UsedInFormsInterface
{

    protected $_forms;

    /**
     *
     * @return UsedInForms
     */
    public function addAdminhtmlCheckoutRegistrationForm()
    {
        return $this->_addForm( 'adminhtml_checkout' );
    }

    /**
     *
     * @return UsedInForms
     */
    public function addAdminhtmlCustomerAddressEditForm()
    {
        return $this->_addForm( 'adminhtml_customer_address' );
    }

    /**
     *
     * @return UsedInForms
     */
    public function addAdminhtmlCustomerInformationEditForm()
    {
        return $this->_addForm( 'adminhtml_customer' );
    }

    /**
     *
     * @return UsedInForms
     */
    public function addCheckoutRegistrationAddressForm()
    {
        return $this->_addForm( 'customer_register_address' );
    }

    /**
     *
     * @return UsedInForms
     */
    public function addCheckoutRegistrationForm()
    {
        return $this->_addForm( 'checkout_register' );
    }

    /**
     *
     * @param string $customForm
     * @return UsedInForms
     */
    public function addCustomForm( $customForm )
    {
        return $this->_addForm( $customForm );
    }

    /**
     *
     * @return UsedInForms
     */
    public function addCustomerAccountInformationEditForm()
    {
        return $this->_addForm( 'customer_account_edit' );
    }

    /**
     *
     * @return UsedInForms
     */
    public function addCustomerAddressEditForm()
    {
        return $this->_addForm( 'customer_address_edit' );
    }

    /**
     *
     * @return UsedInForms
     */
    public function addCustomerRegistrationForm()
    {
        return $this->_addForm( 'customer_account_create' );
    }

    /**
     *
     * @param string $form
     * @return UsedInForms
     */
    protected function _addForm( $form )
    {
        if ( $this->_forms === null ) {
            $this->_forms = array();
        }

        if ( !in_array( $form, $this->_forms ) ) {
            $this->_forms[] = $form;
        }

        return $this;
    }

    /**
     *
     * @return Optional
     */
    public function done()
    {
        $attributes = $this->getParent()->getParent()->getAttributeProperties();
        if (empty($attributes['is_visible']) || empty($attributes['user_defined']) || (!isset($attributes['system']) || $attributes['system'] != 0)) {
            //$this->parent->report()->printWarning('attribute can be used_in_forms only if it is_visible and user_defined and not system');
            //$this->parent->report()->printWarning('fields will be updated');
            $this->getParent()->getParent()->setAttributeCreateProperty('is_visble', true);
            $this->getParent()->getParent()->setAttributeCreateProperty('user_defined', true);
            $this->getParent()->getParent()->setAttributeCreateProperty('system', 0);
        }

        if (empty($attributes['attribute_set_id'])) {
            $this->getParent()->defaultAttributeSetId();
        }

        if (empty($attributes['attribute_group_id'])) {
            $this->getParent()->defaultAttributeGroupId();
        }

        $this->getParent()->getParent()->setAttributeUpdateProperty( 'used_in_forms', $this->_forms );
        return $this->getParent();
    }

}