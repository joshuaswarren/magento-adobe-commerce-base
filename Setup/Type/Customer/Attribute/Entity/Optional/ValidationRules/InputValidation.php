<?php

namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\ValidationRules;


use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\ValidationRules;

class InputValidation
    extends AbstractType
    implements InputValidationInterface
{

    /**
     *
     * @return ValidationRules
     */
    public function date()
    {
        return $this->getParent()->setInputValidation( 'date' );
    }

    /**
     *
     * @return ValidationRules
     */
    public function email()
    {
        return $this->getParent()->setInputValidation( 'email' );
    }

}