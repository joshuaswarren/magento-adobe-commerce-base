<?php
/**
 * Created by PhpStorm.
 * User: jbiesiada
 * Date: 12.12.16
 * Time: 08:27
 */

namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional;


use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\OptionalInterface;

interface UsedInFormsInterface
{

    /**
     * Attribute will be used in Account Information form while creating new customer during placing order in backend.
     *
     * @return UsedInFormsInterface
     */
    public function addAdminhtmlCheckoutRegistrationForm();

    /**
     * Attribute will be used in Account Information form while editing customer information in backend.
     *
     * @return UsedInFormsInterface
     */
    public function addAdminhtmlCustomerInformationEditForm();

    /**
     * Attribute will be used in Customer Address form in backend.
     *
     * @return UsedInFormsInterface
     */
    public function addAdminhtmlCustomerAddressEditForm();

    /**
     * Attribute will be used in Registration form during the checkout.
     *
     * @return UsedInFormsInterface
     */
    public function addCheckoutRegistrationForm();

    /**
     * Attribute will be used in Address form during the registration on the checkout page
     *
     * @return UsedInFormsInterface
     */
    public function addCheckoutRegistrationAddressForm();

    /**
     * Attribute will be used in the registration form.
     *
     * @return UsedInFormsInterface
     */
    public function addCustomerRegistrationForm();

    /**
     * Attribute will be used in Account Information form in front-end.
     *
     * @return UsedInFormsInterface
     */
    public function addCustomerAccountInformationEditForm();

    /**
     * Attribute will be used in Customer Address form in front-end.
     *
     * @return UsedInFormsInterface
     */
    public function addCustomerAddressEditForm();

    /**
     * Attribute will be used in a form with given form code
     *
     * @param string $customForm
     * @return UsedInFormsInterface
     */
    public function addCustomForm( $customForm );

    /**
     * Finish editing attribute forms
     *
     * @return OptionalInterface
     */
    public function done();

}