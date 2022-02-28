<?php

namespace Creatuity\Base\Model\Catalog;

use Creatuity\Base\Helpers\Creatuity;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package waltwo
 * @deprecated use CategoriesModifier instead
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
class CategoriesCreator
{

    /**
     * @var CategoriesModifier
     */
    private $modifier;

    public function __construct(CategoriesModifier $modifier)
    {
        $this->modifier = $modifier;
    }

    /**
     * @deprecated use CategoriesModifier::process() instead.
     */
    public function create($deleteAllCategoriesFirst, array $data, OutputInterface $outputConsole = null)
    {
        $config = [];
        $config['replace_current_categories'] = $deleteAllCategoriesFirst;
        if ($outputConsole) {
            $config['output'] = $outputConsole;
        }
        $this->modifier->process($data, $config);
    }
}
