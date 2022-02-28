<?php namespace Creatuity\Base\CommandLine\Import\Catalog;

use Creatuity\Base\CommandLine\Import\AbstractImportCommand;
use Creatuity\Base\ImportExport\Core\CoreCsvImporter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package m2newbuild
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Products extends AbstractImportCommand
{
    /**
     * @var string
     */
    protected $name = 'creatuity:import:catalog_products';

    /**
     * @var string
     */
    protected $description = 'Imports products from csv file using native Magento tools';

    protected function configure()
    {
        $this->addCsvFileArgument();

        $this->addNativeCoreImporterModeOption();

        $this->addOption(
            'images-base-path',
            'i',
            InputArgument::OPTIONAL,
            'Base directory for images',
            ''
        );

        $this->addOption(
            'multiple-values-separator',
            's',
            InputArgument::OPTIONAL,
            'Multiple values separator. Default is \',\'',
            ','
        );

    }

    protected function runImportCommand(InputInterface $input, OutputInterface $output)
    {
        $this->beginImportMessage($output, $input->getArgument('csv_file'));

        $this->csvImporter->importProductsFromFile(
            $input->getArgument('csv_file'),
            $input->getOption('multiple-values-separator'),
            $input->getOption('images-base-path'),
            $this->getNativeCoreImporterModeOption($input)
        );

        $output->writeln($this->csvImporter->summary());

        $this->endImportMessage($output);
    }

}