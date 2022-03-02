<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Exception;
use Generator;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Filesystem\Directory\WriteFactory;
use Magento\Framework\Module\Dir\Reader as ModuleReader;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Directory\ReadInterface as DirectoryReadInterface;
use Magento\Framework\Filesystem\Directory\WriteInterface as DirectoryWriteInterface;
use Magento\Framework\Filesystem\File\ReadInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Resources extends SubjectAbstract implements SubjectForModuleInterface
{
    private ModuleReader $moduleReader;
    private ReadFactory $readFactory;
    private WriteFactory $writeFactory;
    private DirectoryList $directoryList;
    private SerializerInterface $serializer;
    private string $moduleName;

    public function __construct(
        ModuleReader $moduleReader,
        ReadFactory $readFactory,
        WriteFactory $writeFactory,
        DirectoryList $directoryList,
        Creatuity $creatuity,
        SerializerInterface $serializer
    ) {
        parent::__construct($creatuity);
        $this->moduleReader = $moduleReader;
        $this->readFactory = $readFactory;
        $this->writeFactory = $writeFactory;
        $this->directoryList = $directoryList;
        $this->serializer = $serializer;
    }

    /**
     * @param string $path
     * @param DirectoryReadInterface|string $relativeTo
     * @return string
     * @throws ResourcesHelperException
     * @throws ValidatorException
     */
    public function fileRelPath(string $path, $relativeTo = ''): string
    {
        if ($relativeTo === '') {
            $relativeTo = $this->projectDirReader();
        } elseif (!$relativeTo instanceof DirectoryReadInterface) {
            $relativeTo = $this->readFactory->create($relativeTo);
        }

        $absPath = $this->fileAbsPath($path);

        return $relativeTo->getRelativePath($absPath);
    }

    /**
     * @throws ResourcesHelperException
     */
    public function jsonRead(string $path, array $overrides = [], array $defaults = []): array
    {
        $configJson = $this->fileRead($path, false);

        $jsonContent = [];
        if ($configJson !== null) {
            $jsonContent = $this->serializer->unserialize($configJson);
            if (!is_array($jsonContent)) {
                throw new ResourcesHelperException("Invalid json at: {$path} ");
            }
        }

        return array_replace_recursive($defaults, $jsonContent, $overrides);
    }

    public function fileRead(string $path, bool $mustExists = true): ?string
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

    public function isExists(string $path): bool
    {
        $reader = $this->fileReader($path, false);

        return $reader !== null;
    }

    /**
     * @throws ResourcesHelperException
     */
    public function ensureExists(string $path): void
    {
        $this->fileAbsPath($path, true);
    }

    public function fileReadLines(string $path, bool $mustExists = true, bool $trim = true): Generator
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

    /**
     * @param ReadInterface $reader
     * @return false|string
     */
    private function readLine(ReadInterface $reader)
    {
        try {
            return trim($reader->readLine(65535, "\n"));
        } catch (FileSystemException $e) {
            return false;
        }
    }

    /**
     * @throws ResourcesHelperException
     * @throws FileSystemException
     * @throws Exception
     */
    public function fileReader(string $path, bool $mustExists = true): ?ReadInterface
    {
        $path = $this->fileAbsPath($path, $mustExists);
        if ($path === null) {
            return null;
        }

        return $this->absoluteDirReader(pathinfo($path, PATHINFO_DIRNAME))
            ->openFile(pathinfo($path, PATHINFO_BASENAME));
    }

    /**
     * @throws ResourcesHelperException
     */
    public function fileAbsPath(string $path, bool $mustExists = true): ?string
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

    public function moduleDirReader(string $subDir = ''): ?DirectoryReadInterface
    {
        if ( !$this->moduleName ) {
            return null;
        }
        $moduleRoot = $this->moduleReader->getModuleDir('', $this->moduleName);

        return $this->readFactory->create($moduleRoot . '/' . $subDir);
    }

    public function moduleDirWriter(string $subDir = ''): DirectoryWriteInterface
    {
        throw new ResourcesHelperException('Saving files in modules is forbidden.');
    }

    public function projectDirReader(string $subDir = ''): DirectoryReadInterface
    {
        $projectRoot = $this->directoryList->getRoot();

        return $this->readFactory->create($projectRoot . '/' . $subDir);
    }

    public function projectDirWriter(string $subDir = ''): DirectoryWriteInterface
    {
        $projectRoot = $this->directoryList->getRoot();

        return $this->writeFactory->create($projectRoot . '/' . $subDir);
    }

    /**
     * @throws Exception
     */
    public function absoluteDirReader(string $dir = '/'): DirectoryReadInterface
    {
        if (empty($dir) || $dir == '/') {
            throw new Exception("Since Magento 2.4+, due to security reasons, please do not use root directory as an anchor!");
        }

        return $this->readFactory->create($dir);
    }

    public function absoluteDirWriter(string $dir = '/'): DirectoryWriteInterface
    {
        return $this->writeFactory->create($dir);
    }

    public function forModule(string $moduleName): self
    {
        $this->moduleName = $moduleName;
        return $this;
    }
}

class ResourcesHelperException extends Exception
{}
