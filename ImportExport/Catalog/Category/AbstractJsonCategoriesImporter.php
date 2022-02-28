<?php
namespace Creatuity\Base\ImportExport\Catalog\Category;
use Magento\Framework\Filesystem\DriverPool;


/**
 * @package waltwo
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
abstract class AbstractJsonCategoriesImporter extends AbstractCategoriesImporter
{

    /**
     * @return array
     */
    abstract protected function transformData(array $json);


    /**
     * @return array
     */
    protected function readAndTransformData($absFile)
    {
        $json = $this->readJsonFile($absFile);
        return $this->transformData($json);
    }

    protected function readJsonFile($absFile)
    {
        $reader = $this->readFactory->create($absFile, DriverPool::FILE);
        $hungProtector = 10;
        $content = '';
        while ($buffer = $reader->read(65536)) {
            if (--$hungProtector == 0) {
                throw new \Exception("Problems during reading '{$absFile}' file ");
            }
            $content .= $buffer;
        }
        $jsonObject = \Zend_Json::decode($content);
        if (!is_array($jsonObject)) {
            throw new \Exception("Invalid json file, i think :/");
        }
        return $jsonObject;
    }


}