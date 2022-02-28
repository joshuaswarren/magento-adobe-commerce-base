<?php

namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional;


use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\ValidationRules\InputValidationInterface;
use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\OptionalInterface;

interface ValidationRulesInterface
{

    /**
     * Attribute value's length should not be greater than given value
     *
     * @param int $max
     * @return ValidationRulesInterface
     */
    public function addMaxTextLength( $max );

    /**
     * Attribute value's length should not be lower than given value
     *
     * @param int $min
     * @return ValidationRulesInterface
     */
    public function addMinTextLength( $min );

    /**
     * @return InputValidationInterface
     */
    public function inputValidation();

    /**
     * Finish setting validation rules
     *
     * @return OptionalInterface
     */
    public function done();

}