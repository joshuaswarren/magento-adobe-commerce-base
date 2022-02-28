<?php
namespace Creatuity\Base\CommandLine\Import\Catalog;

use Creatuity\Base\CommandLine\Import\AbstractImportCommand;
use Creatuity\Base\CommandLine\Import\AbstractImportCommandContext;
use Creatuity\Base\ImportExport\Catalog\Category\CategoriesImporterFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package waltwo
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
class Categories extends AbstractImportCommand
{
    /**
     * @var string
     */
    protected $name = 'creatuity:import:catalog_categories';

    /**
     * @var string
     */
    protected $description = 'Imports categories using our own mechanisms';

    /**
     * @var CategoriesImporterFactory
     */
    protected $importerFactory;

    public function __construct(CategoriesImporterFactory $importerFactory, AbstractImportCommandContext $context)
    {
        $this->importerFactory = $importerFactory;

        parent::__construct($context);
    }

    protected function configure()
    {
        $this->addArgument(
            'file',
            InputArgument::REQUIRED,
            'Path to the file. Can be either relative path to project, or either absolute path'
        );

        $this->addArgument(
            'mode',
            InputArgument::REQUIRED,
            $this->importerFactory->describeModes()
        );

    }

    protected function runImportCommand(InputInterface $input, OutputInterface $output)
    {
        $mode = $input->getArgument('mode');
        $absFile = $this->absFilePath($input->getArgument('file'));

        $this->beginImportMessage($output, $input->getArgument('file'));

        $importer = $this->importerFactory->create($mode);
        $importer->import($absFile, $output);

        $this->endImportMessage($output);
    }

}