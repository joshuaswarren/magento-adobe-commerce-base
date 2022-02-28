<?php


namespace Creatuity\Base\Model\Catalog\CategoriesModifier;


use Creatuity\Base\Helpers\ConfigObject;
use Magento\Framework\ObjectManagerInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Config extends ConfigObject
{
    /**
     * @var mixed[]
     */
    protected $defaults = [
        'output' => OutputInterface::class,
        'tree_delimiter' => '/',
        'replace_current_categories' => true,
    ];

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var ObjectManagerInterface[]
     */
    protected $cache = [];


    public function __construct(ObjectManagerInterface $objectManager, $config = [])
    {
        parent::__construct($config);

        $this->objectManager = $objectManager;
    }

    /**
     * @return mixed[]
     */
    protected function defaults()
    {
        return $this->defaults;
    }

    /**
     * @return string
     */
    public function treeDelimiter()
    {
        return (string)$this->get('tree_delimiter');
    }

    /**
     * @return bool
     */
    public function replaceCurrentCategories()
    {
        return (bool)$this->get('replace_current_categories');
    }

    /**
     * @return OutputInterface
     */
    public function output()
    {
        $classOrObject = $this->get('output');

        if ($classOrObject === null) {
            $classOrObject = NullOutput::class;
        }

        if (is_string($classOrObject)) {
            if (!isset($this->cache[$classOrObject])) {
                $this->cache[$classOrObject] = $this->objectManager->get($classOrObject);
            }
            $classOrObject = $this->cache[$classOrObject];
        }

        if ($classOrObject instanceof OutputInterface) {
            return $classOrObject;
        }

        throw new CategoriesModifierException("Unknown output: " . var_export($classOrObject, true));
    }
}