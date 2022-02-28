<?php
namespace Creatuity\Base\CommandLine\Import;

use Creatuity\Base\CommandLine\AbstractCommand;
use Magento\Framework\App\Filesystem\DirectoryList;
use Creatuity\Base\ImportExport\Core\CliCsvImporter;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package m2newbuild
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
abstract class AbstractImportCommand extends AbstractCommand
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


    public function __construct(AbstractImportCommandContext $context)
    {
        parent::__construct();

        $this->csvImporter = $context->getCsvImporter();
        $this->readFactory = $context->getReadFactory();
        $this->directoryList = $context->getDirectoryList();
    }

    public function isEnabled()
    {
        return true;
    }

    protected function runCommand(InputInterface $input, OutputInterface $output)
    {
        $this->runImportCommand($input, $output);
    }

    abstract protected function runImportCommand(InputInterface $input, OutputInterface $output);

    protected function beginImportMessage(OutputInterface $output, $source = null)
    {
        $output->writeln('');
        if ($source) {
            $output->writeln("======== '{$this->importerName()}' importing started from '{$source}' ========");
        } else {
            $output->writeln("======== '{$this->importerName()}' importing started ========");
        }
    }

    protected function endImportMessage(OutputInterface $output)
    {
        $output->writeln("======== '{$this->importerName()}' importing ended ========");
        $output->writeln('');
    }

    protected function importerName()
    {
        return explode(':', $this->getName())[2];
    }

    protected function absFilePath($absOrRelative)
    {
        if (!empty($_SERVER['HOME'])) {
            $absOrRelative = str_replace('~', $_SERVER['HOME'], $absOrRelative);
        }
        $rootPath = $this->directoryList->getRoot();

        $projectRoot = $this->readFactory->create($rootPath);

        if ($projectRoot->isExist($absOrRelative)) {
            return $projectRoot->getAbsolutePath($absOrRelative);
        }

        $systemRoot = $this->readFactory->create('/');
        if ($systemRoot->isExist($absOrRelative)) {
            return $systemRoot->getAbsolutePath($absOrRelative);
        }

        throw new \Exception("Cannot find '${absOrRelative}' file neither absolute neither relative to the project");
    }

    protected function addNativeCoreImporterModeOption($default = 'replace')
    {
        $this->addOption(
            'mode',
            'm',
            InputArgument::OPTIONAL,
            'Native Core Importer mode. Can be one of: append, replace, delete',
            $default
        );
    }

    protected function addCsvFileArgument()
    {
        $this->addArgument(
            'csv_file',
            InputArgument::REQUIRED,
            'Path to the file. Can be either relative path to module, or either relative path to project, or either absolute path'
        );
    }

    protected function getNativeCoreImporterModeOption(InputInterface $input)
    {
        $mode = $input->getOption('mode');
        if (!in_array($mode, ['append', 'replace', 'delete'])) {
            throw new \Exception("Invalid importer mode. Can be one of: append, replace, delete");
        }
        return $mode;
    }

}