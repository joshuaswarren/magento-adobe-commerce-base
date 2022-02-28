<?php namespace Creatuity\Base\Setup\Type\Customer\Attribute\Entity\Required;

use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Required\Input as EavInput;
use Creatuity\Base\Setup\Type\Customer\Attribute\Entity\RequiredInterface;
use Creatuity\Base\Setup\TypeInterface as SetupTypeInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Input extends EavInput implements InputInterface, SetupTypeInterface
{
    const INPUT_TYPES = [
        "INPUT_BOOLEAN" => 'boolean',
        "INPUT_DATE" => 'date',
        "INPUT_GALLERY" => 'gallery',
        "INPUT_HIDDEN" => 'hidden',
        "INPUT_IMAGE" => 'image',
        "INPUT_MEDIA_IMAGE" => 'media_image',
        "INPUT_MULTILINE" => 'multiline',
        "INPUT_MULTISELECT" => 'multiselect',
        "INPUT_PRICE" => 'price',
        "INPUT_SELECT" => 'select',
        "INPUT_TEXT" => 'text',
        "INPUT_TEXTAREA" => 'textarea',
        "INPUT_WEIGHT" => 'weight'
    ];

    /**
     *
     * @return RequiredInterface
     */
    public function multiline()
    {
        return $this->_setInput(self::INPUT_TYPES["INPUT_MULTILINE"]);
    }

}
