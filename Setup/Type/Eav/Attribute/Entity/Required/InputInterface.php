<?php
namespace Creatuity\Base\Setup\Type\Eav\Attribute\Entity\Required;

use Creatuity\Base\Setup\Type\Eav\Attribute\Entity\RequiredInterface;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
interface InputInterface
{

    /**
     *
     * @return RequiredInterface
     */
    public function date();

    /**
     *
     * @return RequiredInterface
     */
    public function text();

    /**
     *
     * @return RequiredInterface
     */
    public function select();

    /**
     *
     * @return RequiredInterface
     */
    public function textarea();

    /**
     *
     * @return RequiredInterface
     */
    public function price();

    /**
     *
     * @return RequiredInterface
     */
    public function mediaImage();

    /**
     *
     * @return RequiredInterface
     */
    public function image();

    /**
     *
     * @return RequiredInterface
     */
    public function file();

    /**
     *
     * @return RequiredInterface
     */
    public function gallery();

    /**
     *
     * @return RequiredInterface
     */
    public function multiselect();

    /**
     *
     * @return RequiredInterface
     */
    public function boolean();

    /**
     *
     * @return RequiredInterface
     */
    public function weight();

    /**
     *
     * @return RequiredInterface
     */
    public function hidden();

    /**
     * Define CSS class that is assigned to the attribute's input element
     *
     * @return RequiredInterface
     */
    public function cssClass( $class );

}
