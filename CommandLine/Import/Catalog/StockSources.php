<?php namespace Creatuity\Base\CommandLine\Import\Catalog;

use Creatuity\Base\CommandLine\Import\AbstractImportCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StockSources extends AbstractImportCommand
{
    /**
     * @var string
     */
    protected $name = 'creatuity:import:catalog_stock_sources';

    /**
     * @var string
     */
    protected $description = 'Imports stock sources from csv file using native Magento tools';

    protected function configure()
    {
        $this->addCsvFileArgument();

        $this->addNativeCoreImporterModeOption();
    }

    protected function runImportCommand(InputInterface $input, OutputInterface $output)
    {
        $this->beginImportMessage($output, $input->getArgument('csv_file'));

        $this->csvImporter->importStockSourcesFromFile(
            $input->getArgument('csv_file'),
            $this->getNativeCoreImporterModeOption($input)
        );

        $output->writeln($this->csvImporter->summary());

        $this->endImportMessage($output);
    }
}
