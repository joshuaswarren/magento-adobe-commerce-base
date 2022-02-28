<?php


namespace Creatuity\Base\Model\Catalog\CategoriesModifier;


use Creatuity\Base\Helpers\Creatuity;

class DataProcessor
{

    /**
     * @var Creatuity
     */
    protected $creatuity;
    /**
     * @var Helper
     */
    protected $helper;
    /**
     * @var Config
     */
    protected $config;


    public function __construct(
        Creatuity $creatuity,
        Helper $helper,
        Config $config
    ) {
        $this->creatuity = $creatuity;
        $this->helper = $helper;
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function processData(array $data)
    {
        $this->log("Cleaning and validating...");
        $data = $this->cleanup($data);
        $data = $this->validate($data);
        $this->log("Building tree...");
        $data = $this->buildTreeKeys($data);
        $this->log("Sorting topologically...");
        $data = $this->sortTopologically($data);
        $this->log("Assigning keys...");
        $data = $this->assignKeys($data);

        $this->log(sprintf("In total we have %s data items", count($data)));

        return $data;
    }

    protected function log($msg)
    {
        $this->helper->log($msg);
    }

    /**
     * @return array
     */
    protected function cleanup(array $categories)
    {
        $position = 10;
        foreach ($categories as &$line) {
            array_walk_recursive($line, function(&$value, $key) { $value = trim($value); });

            if (empty($line['this']) && $line['entity_id']) {
                $line['this'] = $line['entity_id'];
            }

            $line['this'] = trim($line['this'], $this->config->treeDelimiter());

            if (isset($line['parent'])) {
                $line['parent'] = $line['parent'] == $this->config->treeDelimiter() ? $line['parent'] : trim($line['parent'], $this->config->treeDelimiter());
            }

            if (empty($line['entity_id'])) {
                $line['position'] = empty($line['position']) ? $position : (int)$line['position'];
                $line['is_active'] = !isset($line['is_active']) ? true : (bool)$line['is_active'];
                $line['include_in_menu'] = !isset($line['include_in_menu']) ? true : (bool)$line['include_in_menu'];
                if (empty($line['url_key']) && empty($line['entity_id'])) {
                    $line['url_key'] = $this->creatuity->nameToSeoUrlKey($line['name']);
                }
            }

            $position += 10;
        }
        return $categories;
    }

    /**
     * @return array
     */
    protected function validate(array $categories)
    {
        $urlKeys = [];
        foreach ($categories as $item) {
            if (!array_filter($item)) {
                $this->helper->throwError($item, 'Seems to be empty');
            }
            if (empty($item['this'])) {
                $this->helper->throwError($item, "'this' cannot be empty.");
            }
            if (strpos($item['this'], $this->config->treeDelimiter()) !== false) {
                $this->helper->throwError($item, "'this' cannot have '{$this->config->treeDelimiter()}'.");
            }
            if (empty($item['parent']) && empty($item['entity_id'])) {
                $this->helper->throwError($item, "'parent' cannot be empty. Use '{$this->config->treeDelimiter()}' for root.");
            }
            if (empty($item['entity_id']) && $item['name'] == "") {
                $this->helper->throwError($item, '"name" cannot be empty');
            }
            if (isset($urlKeys[$item['this']])) {
                $this->helper->throwError($item, "We're assuming in our importer, that 'this' column is unique.");
            }
            $urlKeys[$item['this']] = true;
        }
        return $categories;
    }

    /**
     * @return array
     */
    protected function buildTreeKeys(array $categories)
    {
        $categoriesByThisKey = array_combine(array_column($categories, 'this'), $categories);

        foreach($categories as &$item) {
            if (empty($item['parent'])) {
                continue;
            }

            $item['parent_key'] = $item['parent'];
            $item['parent'] = $this->buildTreeKeysRecurrently($item['parent'], $categoriesByThisKey);
            if (!is_numeric($item['parent'])) {
                $item['parent'] = $this->config->treeDelimiter() . ltrim($item['parent'], $this->config->treeDelimiter());
                $item['parent'] = strtolower(rtrim($item['parent'], $this->config->treeDelimiter()) . $this->config->treeDelimiter());
            }
        }

        return $categories;
    }

    /**
     * @return string
     */
    protected function buildTreeKeysRecurrently($key, array $categories)
    {
        if ($key == $this->config->treeDelimiter() || $key == '' || is_numeric($key)) {
            return $this->config->treeDelimiter();
        }

        if (strpos($key, $this->config->treeDelimiter()) !== false) {
            return $key;
//            $key = array_reverse(explode($this->config->treeDelimiter(), $key))[0];
        }

        return $this->buildTreeKeysRecurrently($categories[$key]['parent'], $categories) . $categories[$key]['this'] . $this->config->treeDelimiter();
    }

    protected function sortTopologically(array $categories)
    {
        usort($categories, function ($catA, $catB) {
            $diff = substr_count($catA['parent'], $this->config->treeDelimiter()) - substr_count($catB['parent'], $this->config->treeDelimiter());
            if (!$diff) {
                return strcmp($catA['parent'], $catB['parent']);
            }
            return $diff;
        });
        return $categories;
    }

    protected function assignKeys(array $categories)
    {
        $ret = [];
        foreach ($categories as $category) {
            if (empty($category['parent'])) {
                $key = $category['this'];
            } else {
                $parent = $category['parent'];
                if (is_numeric($parent)) {
                    $parent = $this->config->treeDelimiter();
                }

                $key = strtolower($parent . $category['this'] . $this->config->treeDelimiter());
            }

            $ret[$key] = $category;
        }
        return $ret;
    }


}