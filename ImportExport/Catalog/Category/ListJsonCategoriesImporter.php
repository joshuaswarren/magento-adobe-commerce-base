<?php
namespace Creatuity\Base\ImportExport\Catalog\Category;


/**
 * @package waltwo
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
class ListJsonCategoriesImporter extends AbstractJsonCategoriesImporter
{

    /**
     * @return array
     */
    protected function transformData(array $json)
    {
        $ret = [];
        foreach ($json as $key => $element) {
            list($parentKey, $thisKey) = $this->splitKey($key);

            $ret[$key] = [
                    'this' => $thisKey,
                    'parent' => $parentKey,
                ] + $element;
        }

        return $ret;
    }

    protected function splitKey($key)
    {
        $p = (int)strrpos($key, '/');

        $parentKey = substr($key, 0, $p);
        $thisKey = substr($key, $p + 1);

        if (empty($thisKey)) {
            throw new \Exception("Key cannot be empty");
        }

        return [
            $parentKey ? $parentKey : '/',
            $thisKey,
        ];
    }

}


