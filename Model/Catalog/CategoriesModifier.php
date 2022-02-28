<?php

namespace Creatuity\Base\Model\Catalog;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Model\Catalog\CategoriesModifier\CategoriesProcessorInterface;
use Creatuity\Base\Model\Catalog\CategoriesModifier\ConfigFactory;
use Creatuity\Base\Model\Catalog\CategoriesModifier\Examples\AutoDocumentingExample;
use Creatuity\Base\Model\Catalog\CategoriesModifier\Examples\ExampleInterface;
use Creatuity\Base\Model\Catalog\CategoriesModifier\ExamplesFactory;
use Creatuity\Base\Model\Catalog\CategoriesModifier\ExamplesFactoryInterface;

/**
 * @package waltwo
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
class CategoriesModifier
{

    /**
     * @var CategoriesProcessorInterface
     */
    private $categoriesProcessor;
    /**
     * @var ExamplesFactoryInterface
     */
    private $examplesFactory;
    /**
     * @var ConfigFactory
     */
    private $configFactory;

    public function __construct(
        CategoriesProcessorInterface $categoriesProcessor,
        ExamplesFactoryInterface $examplesFactory,
        ConfigFactory $configFactory
    ) {
        $this->categoriesProcessor = $categoriesProcessor;
        $this->examplesFactory = $examplesFactory;
        $this->configFactory = $configFactory;
    }

    /**
     * @see AutoDocumentingExample to know the $data format
     */
    public function process(array $data, array $config = [])
    {
        $configObject = $this->configFactory->create($config);
        $this->categoriesProcessor->process($configObject, $data);
    }

    /**
     * @see ExampleInterface to find all available examples
     */
    public function processDemo($demoTypeOrClass = null, array $config = [])
    {
        $example = $this->examplesFactory->create($demoTypeOrClass);

        $this->process($example->demo(), $config);
    }

}
