<?php


namespace Creatuity\Base\Model\Catalog\CategoriesModifier;


use Magento\Framework\ObjectManagerInterface;

class ConfigFactory
{
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $configClass;

    public function __construct(
        ObjectManagerInterface $objectManager,
        $configClass = Config::class
    ) {
        $this->objectManager = $objectManager;
        $this->configClass = $configClass;
    }

    /**
     * @return Config
     */
    public function create($config = [], $override = null)
    {
        $result = $this->objectManager->create($this->configClass, [
            'config' => $config
        ]);

        if ($override) {
            $result = Config::merge($result, $override);
        }

        return $result;
    }

}