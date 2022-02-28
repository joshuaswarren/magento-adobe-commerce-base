<?php
namespace Creatuity\Base\CommandLine\Import;

use Creatuity\Base\ImportExport\Core\CliCsvImporter;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\ObjectManager\ContextInterface;

/**
 * @package waltwo
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
class AbstractImportCommandContext implements ContextInterface
{
    /**
     * @var CliCsvImporter
     */
    protected $csvImporter;

    /**
     * @var ReadFactory
     */
    protected $readFactory;

    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * AbstractImportCommandContext constructor.
     * @param CliCsvImporter $csvImporter
     * @param ReadFactory $readFactory
     * @param DirectoryList $directoryList
     */
    public function __construct(CliCsvImporter $csvImporter, ReadFactory $readFactory, DirectoryList $directoryList)
    {
        $this->csvImporter = $csvImporter;
        $this->readFactory = $readFactory;
        $this->directoryList = $directoryList;
    }

    /**
     * @return DirectoryList
     */
    public function getDirectoryList()
    {
        return $this->directoryList;
    }

    /**
     * @return CliCsvImporter
     */
    public function getCsvImporter()
    {
        return $this->csvImporter;
    }

    /**
     * @return ReadFactory
     */
    public function getReadFactory()
    {
        return $this->readFactory;
    }


}