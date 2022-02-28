<?php


namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\ValidationRules;


use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\ValidationRulesInterface;

interface InputValidationInterface
{

    /**
     * Check if attribute value has correct date format
     *
     * @return ValidationRulesInterface
     */
    public function date();

    /**
     * Check if attribute value has correct email format
     *
     * @return ValidationRulesInterface
     */
    public function email();

}