<?php

namespace Creatuity\Base\Model\CsvParser;

use Magento\Framework\Filesystem\DriverPool;
use Magento\Framework\Filesystem\File\ReadFactory as FileReadFactory;
use Magento\Framework\Filesystem\File\ReadInterface as FileReadInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class CsvFile
{
    /** @var FileReadInterface */
    protected $file;

    protected $bomHandled;


    public function __construct($filePath, FileReadFactory $fileReadFactory)
    {
        $this->file = $fileReadFactory->create($filePath, DriverPool::FILE);
        $this->bomHandled = false;
    }

    public function __destruct()
    {
        try {
            $this->close();
        } catch ( \Exception $e ) {}
    }

    /**
     * @param string $separator
     * @param string $enclosure
     * @param string $escapeChar
     * @return array
     */
    public function readCsv($separator, $enclosure, $escapeChar)
    {
        if ( !$this->file ) {
            throw new \Exception('File has been closed. You can reopen it in new class instance');
        }

        $fileCsvLine = $this->file->readCsv(0, $separator, $enclosure, $escapeChar);

        if ( isset($fileCsvLine[0]) && $fileCsvLine[0] !== null ) {
            if ( !$this->bomHandled ) {
                $fileCsvLine[0] = $this->removeBOM($fileCsvLine[0]);
                $this->bomHandled = true;
            }
            return $fileCsvLine;
        }
        return [];
    }

    /**
     * @param $string
     * @return string
     */
    protected function removeBOM($string)
    {
        return trim($string, chr(0xEF) . chr(0xBB) . chr(0xBF));
    }

    public function close()
    {
        if ( !$this->file) {
            return $this;
        }

        $this->file->close();
        $this->file = null;
    }
}