<?php namespace Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Required;

use Creatuity\Base\Setup\AbstractType;
use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\RequiredInterface;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Input extends AbstractType implements InputInterface, SetupTypeInterface
{
    const INPUT_TYPES = [
        "INPUT_BOOLEAN" => 'boolean',
        "INPUT_DATE" => 'date',
        "INPUT_GALLERY" => 'gallery',
        "INPUT_HIDDEN" => 'hidden',
        "INPUT_IMAGE" => 'image',
        "INPUT_MEDIA_IMAGE" => 'media_image',
        "INPUT_MULTISELECT" => 'multiselect',
        "INPUT_PRICE" => 'price',
        "INPUT_SELECT" => 'select',
        "INPUT_TEXT" => 'text',
        "INPUT_TEXTAREA" => 'textarea',
        "INPUT_WEIGHT" => 'weight',
        "INPUT_FILE" => 'file'
    ];

    /**
     *
     * @return RequiredInterface
     */
    public function boolean()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_BOOLEAN"]);
    }

    /**
     *
     * @return RequiredInterface
     */
    public function date()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_DATE"]);
    }

    /**
     *
     * @return RequiredInterface
     */
    public function gallery()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_GALLERY"]);
    }

    /**
     *
     * @return RequiredInterface
     */
    public function hidden()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_HIDDEN"]);
    }

    /**
     *
     * @return RequiredInterface
     */
    public function image()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_IMAGE"]);
    }

    /**
     *
     * @return RequiredInterface
     */
    public function file()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_FILE"]);
    }

    /**
     *
     * @return RequiredInterface
     */
    public function mediaImage()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_MEDIA_IMAGE"]);
    }

    /**
     *
     * @return RequiredInterface
     */
    public function multiselect()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_MULTISELECT"]);
    }

    /**
     *
     * @return RequiredInterface
     */
    public function price()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_PRICE"]);
    }

    /**
     *
     * @return RequiredInterface
     */
    public function select()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_SELECT"]);
    }

    /**
     *
     * @return RequiredInterface
     */
    public function text()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_TEXT"]);
    }

    /**
     *
     * @return RequiredInterface
     */
    public function textarea()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_TEXTAREA"]);
    }

    /**
     *
     * @return RequiredInterface
     */
    public function weight()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_WEIGHT"]);
    }

    /**
     * Define CSS class that is assigned to the attribute's input element
     *
     * @return RequiredInterface
     */
    public function cssClass($class)
    {
        $this->getParent()->getParent()->setFrontendClass($class);
        return $this->getParent();
    }

    /**
     *
     * @param string $input
     * @return RequiredInterface
     */
    protected function _setInput($input)
    {
        $this->getParent()->getParent()->setAttributeCreateProperty('input', $input);
        return $this->getParent();
    }
}
