<?php
namespace Creatuity\Base\ImportExport\Catalog\Category;



/**
 * @package waltwo
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
class LinkedListJsonCategoriesImporter extends AbstractJsonCategoriesImporter
{

    /**
     * @return array
     */
    protected function transformData(array $json)
    {
        // We do not modify entries because it's a native behavior
        // of Creatuity\Base\Model\Catalog\CategoriesModifier class
        return $json;
    }

}


