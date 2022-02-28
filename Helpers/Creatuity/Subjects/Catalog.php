<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\ImportExport\Catalog\Category\CategoriesImporterFactory;
use Creatuity\Base\ImportExport\Core\CliCsvImporter;
use Creatuity\Base\Model\Catalog\CategoriesModifier;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class Catalog extends SubjectAbstract implements SubjectForModuleInterface
{
    /**
     * @var CliCsvImporter
     */
    protected $csvImporter;

    /**
     * @var Creatuity
     */
    protected $helper;

    /**
     * @var CategoriesImporterFactory
     */
    protected $categoriesImporterFactory;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var CategoriesModifier
     */
    protected $categoriesModifier;

    public function __construct(
        CliCsvImporter $csvImporter,
        CategoriesImporterFactory $categoriesImporterFactory,
        CategoriesModifier $categoriesModifier,
        Creatuity $creatuity
    ) {
        parent::__construct($creatuity);

        $this->csvImporter = $csvImporter;
        $this->categoriesImporterFactory = $categoriesImporterFactory;
        $this->categoriesModifier = $categoriesModifier;
    }


    public function importProducts($pathToCsv, $multipleCategorySeparator = ',', $importImagesDir = '', $method = 'append')
    {
        $absPath = $this->creatuity()->resources($this->moduleName)->fileAbsPath($pathToCsv);

        $this->creatuity()->report()->printMessage("Starting importing products from '{$absPath}'...");
        $this->csvImporter->importProductsFromFile($absPath, $multipleCategorySeparator, $importImagesDir, $method);
        $this->creatuity()->report()->printEmptySeparator();

        if ($this->csvImporter->hasErrors()) {
            $this->creatuity()->report()->printError($this->csvImporter->summary());
            throw new \Exception("Cannot import products from '$pathToCsv' !'");
        }

        $this->creatuity()->report()->printSuccess($this->csvImporter->summary());
    }

    public function importCategories($pathToFile, $mode, array $config = [])
    {
        $absPath = $this->creatuity()->resources($this->moduleName)->fileAbsPath($pathToFile);

        $this->creatuity()->report()->printMessage("Start importing categories from '{$absPath}'...");

        $this->categoriesImporterFactory->create($mode)->import($absPath, $this->output(), $config);

        $this->creatuity()->report()->printSuccess("Categories import done.");
    }

    public function setupExampleCategories($demoTypeOrClass = 'install_simple_tree', array $config = [])
    {
        $this->categoriesModifier->processDemo($demoTypeOrClass, $config + [
            'output' => $this->output(),
        ]);
    }

    public function setupCategories(array $data, array $config = [])
    {
        $this->creatuity()->report()->printMessage("Start setting up categories ...");

        $this->categoriesModifier->process($data, $config + [
            'output' => $this->output(),
        ]);

        $this->creatuity()->report()->printSuccess("Categories setup done.");
    }

    /**
     * @return OutputInterface
     */
    protected function output()
    {
        return $this->creatuity()->report()->output();
    }

    /**
     * @param string $moduleName
     * @return $this
     */
    public function forModule($moduleName)
    {
        $this->moduleName = $moduleName;
        return $this;
    }
}
