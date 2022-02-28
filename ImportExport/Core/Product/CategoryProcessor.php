<?php

namespace Creatuity\Base\ImportExport\Core\Product;

use Magento\CatalogImportExport\Model\Import\Product\CategoryProcessor as CoreCategoryProcessor;

/**
 * @category Creatuity
 * @package intb2
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 * @license https://warrenappliedlabs.com/license
 */
class CategoryProcessor extends CoreCategoryProcessor
{
    protected function upsertCategory($categoryPath)
    {
        if (isset($this->categories[$categoryPath])) {
            return $this->categories[$categoryPath];
        }
        $categoryPath = strtolower($categoryPath);

        if (isset($this->categories[$categoryPath])) {
            return $this->categories[$categoryPath];
        }

        return null;
    }

    public function upsertCategories($categoriesString, $categoriesSeparator)
    {
        return array_filter(
            parent::upsertCategories($categoriesString, $categoriesSeparator)
        );
    }
}