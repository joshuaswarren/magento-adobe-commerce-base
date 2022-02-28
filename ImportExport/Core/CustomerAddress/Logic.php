<?php

namespace Creatuity\Base\ImportExport\Core\CustomerAddress;

use Creatuity\Base\Helpers\Database;
/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class Logic
{
    /**
     * @var int
     */
    protected $counter = 0;

    /**
     * @var Database
     */
    protected $databaseHelper;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    public function __construct(Database $databaseHelper, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->databaseHelper = $databaseHelper;
        $this->output = $output;
    }

    /**
     * @return array
     */
    public function process(array $addRows, array $updateRows)
    {
        $this->output->writeln("Progress: " . ($this->counter += sizeof($addRows) + sizeof($updateRows)));
        return [
            $this->databaseHelper->normalizeDataSetForMultipleInsert($addRows),
            $updateRows,
        ];
    }
}