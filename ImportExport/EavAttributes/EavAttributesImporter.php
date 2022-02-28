<?php

namespace Creatuity\Base\ImportExport\EavAttributes;

use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Filesystem\DriverPool;
use Magento\Framework\Filesystem\File\ReadFactory;

class EavAttributesImporter
{
    /** @var EavSetup  */
    protected $eavSetup;
    /** @var string|int */
    protected $entityTypeId;
    /** @var ReadFactory  */
    protected $readFactory;

    public function __construct(EavSetup $eavSetup, ReadFactory $readFactory, $entityTypeId)
    {
        $this->eavSetup = $eavSetup;
        $this->entityTypeId = $entityTypeId;
        $this->readFactory = $readFactory;
    }

    public function run($absFile)
    {
        $data = $this->readJson($absFile);

        foreach ($data as $attribute) {
            $this->addAttribute($attribute);
        }
    }

    protected function addAttribute($attribute)
    {
        $this->eavSetup->removeAttribute($this->entityTypeId, $attribute['attribute_code']);
        $this->eavSetup->addAttribute($this->entityTypeId, $attribute['attribute_code'], $attribute);
    }

    protected function readJson($absFile)
    {
        $reader = $this->readFactory->create($absFile, DriverPool::FILE);
        $hungProtector = 10;
        $content = '';
        while ($buffer = $reader->read(65536)) {
            if (--$hungProtector == 0) {
                throw new \Exception("Problems during reading '${absFile}' file ");
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