<?php namespace Creatuity\Base\ImportExport\Plugin;

use Magento\ImportExport\Model\Source\Import\Entity;

/**
 *
 * @package Project
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class ImportExportHideValue
{

    public function afterToOptionArray(Entity $subject, $data)
    {
        unset($data[ array_search('creatuity_customer_address', array_column($data, 'value')) ]);

        return $data;
    }
}