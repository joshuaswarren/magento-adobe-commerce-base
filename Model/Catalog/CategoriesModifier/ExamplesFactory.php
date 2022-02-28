<?php

namespace Creatuity\Base\Model\Catalog\CategoriesModifier;


use Creatuity\Base\Model\Catalog\CategoriesModifier\Examples\ExampleInterface;
use Creatuity\Base\Model\Catalog\CategoriesModifier\Examples\InstallSimpleTreeExample;
use Creatuity\Base\Model\Catalog\CategoriesModifier\Examples\SimpleCategoriesModificationExample;
use Magento\Framework\ObjectManagerInterface;

class ExamplesFactory implements ExamplesFactoryInterface
{

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $defaultType;


    public function __construct(
        ObjectManagerInterface $objectManager,
        $defaultType = InstallSimpleTreeExample::class
    ) {
        $this->objectManager = $objectManager;
        $this->defaultType = $defaultType;
    }

    /**
     * @return ExampleInterface
     */
    public function create($type, array $args = [])
    {
        if (empty($type))  {
            return $this->create($this->defaultType, $args);
        }

        $class = class_exists($type)
            ? $type
            : $this->typeToClass($type, $args);

        return $this->createObject($class, $args);
    }

    /**
     * @return string
     */
    protected function typeToClass($type, array $args)
    {
        $className = ucwords($type, '_');
        $className = str_replace('_', '', $className);
        return "Creatuity\\Base\\Model\\Catalog\\CategoriesModifier\\Examples\\{$className}Example";
    }

    /**
     * @return ExampleInterface
     */
    protected function createObject($type, array $args)
    {
        $object = $this->objectManager->create($type, $args);
        if (!$object instanceof ExampleInterface) {
            throw new CategoriesModifierException("Expected " . ExampleInterface::class);
        }
        return $object;
    }

}