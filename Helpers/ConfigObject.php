<?php


namespace Creatuity\Base\Helpers;

abstract class ConfigObject
{

    /**
     * @return ConfigObject
     */
    public static function merge(ConfigObject $config, $override)
    {
        if ($override instanceof ConfigObject) {
            return static::override($config, $override->data);
        } elseif (is_array($override )) {
            return static::override($config, $override);
        }

        throw new \Exception("Expected array or " . static::class);
    }

    /**
     * @return ConfigObject
     */
    public static function override(ConfigObject $config, ...$overrides)
    {
        $result = $config->cloneObject();
        foreach($overrides as $override) {
            $result->data = $result->mergeData($config->data, $override);
        }
        return $result;
    }

    /**
     * @var mixed[]
     */
    private $defaults = [];

    /**
     * @var mixed[]
     */
    private $data = [];

    public function __construct($config = [])
    {
        $this->data = $config;
        $this->defaults = $this->defaults();
    }

    /**
     * @return mixed[]
     */
    abstract protected function defaults();


    /**
     * @return array
     */
    protected function mergeData(array $originalData, array $overrideData)
    {
        return $overrideData + $originalData;
    }

    /**
     * @return ConfigObject
     */
    protected function cloneObject()
    {
        return clone $this;
    }

    /**
     * @return mixed
     */
    protected function get($key)
    {
        if (!array_key_exists($key, $this->data)) {
            return $this->defaults[$key];
        }
        return $this->data[$key];
    }

}