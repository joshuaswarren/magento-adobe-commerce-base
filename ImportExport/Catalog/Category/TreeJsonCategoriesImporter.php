<?php
namespace Creatuity\Base\ImportExport\Catalog\Category;


/**
 * @package waltwo
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
class TreeJsonCategoriesImporter extends AbstractJsonCategoriesImporter
{

    /**
     * @return array
     */
    protected function transformData(array $json)
    {
        $results = [];
        $this->transformElements($results, $json, '/');
        return array_reverse($results);
    }

    protected function transformElements(array &$results, $elements, $parent)
    {
        foreach ($elements as $element) {
            $this->transformElement($results, $element, $parent);
        }
    }

    protected function transformElement(array &$results, $element, $parentKey)
    {
        if (!empty($element['is_config_node'])) {
            $results["__creatuity_config_node__"] = $element;
            return;
        }

        $thisKey = $this->helper->nameToSeoUrlKey($element['name']);
        $wholeKey = rtrim($parentKey, '/') . '/' . $thisKey;

        $self = [
                'this' => $thisKey,
                'parent' => $parentKey,
            ] + $element;

        unset($self['sub']);

        if (!empty($element['sub'])) {
            $this->transformElements($results, $element['sub'], $wholeKey);
        }

        $results[$wholeKey] = $self;
    }

}


