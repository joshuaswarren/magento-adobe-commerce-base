<?php

namespace Creatuity\Base\CommandLine\Import\EavAttributes;

use Creatuity\Base\CommandLine\Import\AbstractImportCommand;
use Creatuity\Base\CommandLine\Import\AbstractImportCommandContext;
use Creatuity\Base\ImportExport\EavAttributes\EavAttributesImporter;
use Creatuity\Base\ImportExport\EavAttributes\EavAttributesImporterFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Product extends AbstractImportCommand
{
    const ENTITY_TYPE = 'catalog_product';

    /**
     * @var string
     */
    protected $name = 'creatuity:import:catalog_product_eav_attributes';

    /**
     * @var string
     */
    protected $description = 'Imports product EAV attributes using our own mechanisms';

    /**
     * @var EavAttributesImporterFactory
     */
    protected $attributesImporterFactory;

    public function __construct(
        EavAttributesImporterFactory $attributesImporterFactory,
        AbstractImportCommandContext $context
    ) {
        parent::__construct($context);

        $this->attributesImporterFactory = $attributesImporterFactory;
    }

    protected function configure()
    {
        $this->addArgument(
            'file',
            InputArgument::REQUIRED,
            'Path to the file. Can be either relative path to project, or either absolute path'
        );
    }

    protected function runImportCommand(InputInterface $input, OutputInterface $output)
    {
        $this->beginImportMessage($output, $input->getArgument('file'));

        /** @var EavAttributesImporter $importer */
        $importer = $this->attributesImporterFactory->create(self::ENTITY_TYPE);
        $importer->run($input->getArgument('file'));

        $this->endImportMessage($output);
    }

}