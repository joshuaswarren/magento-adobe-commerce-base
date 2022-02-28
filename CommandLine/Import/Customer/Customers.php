<?php namespace Creatuity\Base\CommandLine\Import\Customer;

use Creatuity\Base\CommandLine\Import\AbstractImportCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @package waltwo
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
class Customers extends AbstractImportCommand
{
    /**
     * @var string
     */
    protected $name = 'creatuity:import:customers';

    /**
     * @var string
     */
    protected $description = 'Imports customers from csv file using native Magento tools';


    protected function configure()
    {
        $this->addCsvFileArgument();

        $this->addNativeCoreImporterModeOption();
    }

    protected function runImportCommand(InputInterface $input, OutputInterface $output)
    {
        $this->beginImportMessage($output, $input->getArgument('csv_file'));

        $this->csvImporter->importCustomersMainFromFile(
            $input->getArgument('csv_file'),
            $this->getNativeCoreImporterModeOption($input)
        );

        $output->writeln($this->csvImporter->summary());

        $this->endImportMessage($output);
    }


}