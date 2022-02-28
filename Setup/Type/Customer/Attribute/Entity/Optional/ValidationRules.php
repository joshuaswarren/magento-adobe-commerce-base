<?php
/**
 * Created by PhpStorm.
 * User: jbiesiada
 * Date: 19.12.16
 * Time: 08:49
 */

namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional;


use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional;
use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Optional\ValidationRules\InputValidation;

class ValidationRules
    extends AbstractType
    implements ValidationRulesInterface
{

    protected $_rules;

    /**
     *
     * @return InputValidation
     */
    public function inputValidation()
    {
        return $this->getContext()->getTypeFactory()->create( 'customer_attribute_entity_optional_validationRules_inputValidation', $this );
    }

    /**
     *
     * @param string $inputValidation
     * @return ValidationRules
     */
    public function setInputValidation( $inputValidation )
    {
        return $this->_addRule( 'input_validation', $inputValidation );
    }

    /**
     *
     * @param int $max
     * @return ValidationRules
     */
    public function addMaxTextLength( $max )
    {
        return $this->_addRule( 'max_text_length', $max );
    }

    /**
     *
     * @param int $min
     * @return ValidationRules
     */
    public function addMinTextLength( $min )
    {
        return $this->_addRule( 'min_text_length', $min );
    }

    /**
     *
     * @param string $key
     * @param string $value
     * @return ValidationRules
     */
    protected function _addRule( $key, $value )
    {
        if ( $this->_rules === null ) {
            $this->_rules = array();
        }
        $this->_rules[ $key ] = $value;

        return $this;
    }

    /**
     *
     * @return Optional
     */
    public function done()
    {
        $this->getParent()->getParent()->setAttributeCreateProperty( 'validate_rules', $this->_rules );
        return $this->getParent();
    }

}