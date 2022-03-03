<?php

namespace Creatuity\Base\Model\CsvParser;

use Exception;
use Magento\Framework\Filesystem\DriverPool;
use Magento\Framework\Filesystem\File\ReadFactory as FileReadFactory;
use Magento\Framework\Filesystem\File\ReadInterface as FileReadInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class CsvFile
{
    private ?FileReadInterface $file;

    private bool $bomHandled = false;

    public function __construct(string $filePath, FileReadFactory $fileReadFactory)
    {
        $this->file = $fileReadFactory->create($filePath, DriverPool::FILE);
    }

    public function __destruct()
    {
        try {
            $this->close();
        } catch (Exception $e) {}
    }

    /**
     * @throws Exception
     */
    public function readCsv(string $separator, string $enclosure, string $escapeChar): array
    {
        if (!$this->file) {
            throw new Exception('File has been closed. You can reopen it in new class instance');
        }

        $fileCsvLine = $this->file->readCsv(0, $separator, $enclosure, $escapeChar);

        if (isset($fileCsvLine[0]) && $fileCsvLine[0] !== null) {
            if (!$this->bomHandled) {
                $fileCsvLine[0] = $this->removeBOM($fileCsvLine[0]);
                $this->bomHandled = true;
            }

            return $fileCsvLine;
        }

        return [];
    }

    public function close(): void
    {
        if (!$this->file) {
            return;
        }

        $this->file->close();
        $this->file = null;
    }

    private function removeBOM(string $string): string
    {
        return trim($string, chr(0xEF) . chr(0xBB) . chr(0xBF));
    }
}
