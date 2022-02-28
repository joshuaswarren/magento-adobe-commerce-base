<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Filesystem\Directory\WriteFactory;
use Magento\Framework\Module\Dir\Reader as ModuleReader;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Directory\ReadInterface as DirectoryReadInterface;
use Magento\Framework\Filesystem\Directory\WriteInterface as DirectoryWriteInterface;
use Magento\Framework\Filesystem\File\ReadInterface as FileReadInterface;
use Magento\Framework\Filesystem\File\ReadInterface;
use Magento\Framework\Exception\FileSystemException;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class Resources extends SubjectAbstract implements SubjectForModuleInterface
{
    /**
     * @var ModuleReader
     */
    protected $moduleReader;

    /**
     * @var ReadFactory
     */
    protected $readFactory;

    /**
     * @var WriteFactory
     */
    protected $writeFactory;

    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * @var string
     */
    protected $moduleName;

    public function __construct(
        ModuleReader $moduleReader,
        ReadFactory $readFactory,
        WriteFactory $writeFactory,
        DirectoryList $directoryList,
        Creatuity $creatuity
    ) {
        parent::__construct($creatuity);

        $this->moduleReader = $moduleReader;
        $this->readFactory = $readFactory;
        $this->writeFactory = $writeFactory;
        $this->directoryList = $directoryList;
    }

    /**
     * @param string $path
     * @param DirectoryReadInterface|string $relativeTo
     * @return string
     */
    public function fileRelPath($path, $relativeTo = '')
    {
        if ($relativeTo === '') {
            $relativeTo = $this->projectDirReader();
        } elseif (!$relativeTo instanceof DirectoryReadInterface) {
            $relativeTo = $this->readFactory->create($relativeTo);
        }
        $absPath = $this->fileAbsPath($path);

        return $relativeTo->getRelativePath($absPath);
    }

    public function jsonRead($path, array $overrides = [], $defaults = [])
    {
        $configJson = $this->fileRead($path, false);

        $jsonContent = [];
        if ($configJson !== null) {
            $jsonContent = json_decode($configJson, true);
            if (!is_array($jsonContent)) {
                throw new ResourcesHelperException("Invalid json at: {$path} ");
            }
        }

        return array_replace_recursive($defaults, $jsonContent, $overrides);
    }

    /**
     * @return string
     */
    public function fileRead($path, $mustExists = true)
    {
        $reader = $this->fileReader($path, $mustExists);
        if ($reader === null) {
            return null;
        }

        if (method_exists($reader, 'readAll')) {
            return $reader->readAll();
        }

        $content = '';
        while (!$reader->eof()) {
            $content .= $reader->read(1024 * 4);
        }

        return $content;
    }

    public function isExists($path)
    {
        $reader = $this->fileReader($path, false);

        return $reader !== null;
    }

    public function ensureExists($path)
    {
        $this->fileAbsPath($path, true);
    }

    /**
     * @return \Generator|string[]
     */
    public function fileReadLines($path, $mustExists = true, $trim = true)
    {
        $reader = $this->fileReader($path, $mustExists);

        if ($reader === null) {
            return;
        }

        if ($trim) {
            while('' !== ($line = trim($this->readLine($reader)))) {
                yield $line;
            }
        } else {
            while(false !== ($line = $this->readLine($reader))) {
                yield $line;
            }
        }
    }


    protected function readLine(ReadInterface $reader)
    {
        try {
            return trim($reader->readLine(65535, "\n"));
        } catch (FileSystemException $e) {
            return false;
        }
    }

    /**
     * @return FileReadInterface
     */
    public function fileReader($path, $mustExists = true)
    {
        $path = $this->fileAbsPath($path, $mustExists);
        if ($path === null) {
            return null;
        }

        return $this->absoluteDirReader(pathinfo($path, PATHINFO_DIRNAME))
            ->openFile(pathinfo($path, PATHINFO_BASENAME));
    }

    public function fileAbsPath($path, $mustExists = true)
    {
        $moduleReader = $this->moduleDirReader();
        if ($moduleReader && $moduleReader->isExist($path)) {
            return $moduleReader->getAbsolutePath($path);
        }

        $projectReader = $this->projectDirReader();
        if ($projectReader->isExist($path)) {
            return $projectReader->getAbsolutePath($path);
        }

        $absoluteReader = $this->absoluteDirReader(pathinfo($path, PATHINFO_DIRNAME));
        if ($absoluteReader->isExist($path)) {
            return $absoluteReader->getAbsolutePath($path);
        }

        if (!$mustExists) {
            return null;
        }

        throw new ResourcesHelperException(
            $this->moduleName ?
                "Cannot find '$path' file, neither for {$this->moduleName} module, neither for project, neither absolute" :
                "Cannot find '$path' file, neither for project, neither absolute. Perhaps you should define module name to look for file?"
        );
    }

    /**
     * @return DirectoryReadInterface
     */
    public function moduleDirReader($subDir = '')
    {
        if ( !$this->moduleName ) {
            return null;
        }
        $moduleRoot = $this->moduleReader->getModuleDir('', $this->moduleName);

        return $this->readFactory->create($moduleRoot . '/' . $subDir);
    }

    /**
     * @return DirectoryWriteInterface
     */
    public function moduleDirWriter($subDir = '')
    {
        throw new ResourcesHelperException('Saving files in modules is forbidden.');
    }

    /**
     * @return DirectoryReadInterface
     */
    public function projectDirReader($subDir = '')
    {
        $projectRoot = $this->directoryList->getRoot();

        return $this->readFactory->create($projectRoot . '/' . $subDir);
    }

    /**
     * @return DirectoryWriteInterface
     */
    public function projectDirWriter($subDir = '')
    {
        $projectRoot = $this->directoryList->getRoot();

        return $this->writeFactory->create($projectRoot . '/' . $subDir);
    }

    /**
     * @return DirectoryReadInterface
     */
    public function absoluteDirReader($dir = '/')
    {
        if (empty($dir) || $dir == '/') {
            throw new \Exception("Since Magento 2.4+, due to security reasons, please do not use root directory as an anchor!");
        }
        return $this->readFactory->create($dir);
    }

    /**
     * @return DirectoryWriteInterface
     */
    public function absoluteDirWriter($dir = '/')
    {
        return $this->writeFactory->create($dir);
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

class ResourcesHelperException extends \Exception
{}
