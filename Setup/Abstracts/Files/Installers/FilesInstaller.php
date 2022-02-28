<?php

namespace Creatuity\Base\Setup\Abstracts\Files\Installers;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Subjects\ResourcesHelperException;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class FilesInstaller
{
    /**
     * @var string
     */
    protected $filesDir = 'data/files';

    /**
     * @var Creatuity
     *
     * @deprecated use $this->creatuity() method
     */
    protected $creatuity;

    /**
     * @var string
     */
    protected $className;

    /**
     * @param string $className
     */
    public function __construct(Creatuity $creatuity, $className)
    {
        $this->creatuity = $creatuity;
        $this->className = $className;
    }


    /**
     * Example:
     *   ->installByDirs([
     *      'pub/media' => [
     *          'test1.jpg',
     *          'test2.jpg',
     *      ],
     *      'pub/media/catalog/' => [
     *          'test3.jpg',
     *       ],
     *   ]);
     *
     */
    public function installByDirs(array $dirsWithFiles)
    {
        $isOk = true;

        foreach ($dirsWithFiles as $dstDirInProject => $srcFiles) {
            foreach ((array)$srcFiles as $srcPathInModule) {
                try{
                    $this->installModuleDir($srcPathInModule, $dstDirInProject);
                } catch (\Exception $e) {
                    $isOk = false;
                    $this->creatuity()->report()->printError($e->getMessage(), $e);
                }
            }
        }

        return $isOk;
    }

    /**
     * Example:
     *   ->installByFiles([
     *      'test1.jpg' => 'pub/media',
     *      'test2.jpg' => 'pub/media/catalog/',
     *   ]);
     *
     */
    public function installByFiles(array $filesToDirs)
    {
        $isOk = true;

        foreach ($filesToDirs as $srPathInModule => $dstPathsInProject) {
            foreach ((array)$dstPathsInProject as $dstPathInProject) {
                try{
                    $this->installModuleFile($srPathInModule, $dstPathInProject);
                } catch (\Exception $e) {
                    $isOk = false;
                    $this->creatuity()->report()->printError($e->getMessage(), $e);
                }
            }
        }

        return $isOk;
    }

    protected function installModuleFile($srPathInModule, $dstPathInProject)
    {
        $projectDirReader = $this->creatuity()->resources()->projectDirReader();

        $srcPathInProject = $this->determineSrcPath($srPathInModule);

        if ($projectDirReader->isDirectory($dstPathInProject)) {
            $dstPathInProject = rtrim($dstPathInProject, '/') . '/' . basename($srcPathInProject);
        }

        $this->installFile($srcPathInProject, $dstPathInProject);
    }

    protected function installModuleDir($srcPathInModule, $dstDirInProject)
    {
        $srcPathInProject = $this->determineSrcPath($srcPathInModule);

        $dstPathInProject = rtrim($dstDirInProject, '/') . '/' . basename($srcPathInProject);

        $this->installFile($srcPathInProject, $dstPathInProject);
    }

    protected function installFile($srcPath, $dstPathInProject)
    {
        $projectDirWriter = $this->creatuity()->resources()->projectDirWriter();

        if (!$projectDirWriter->isExist($srcPath)) {
            $srcPath = $projectDirWriter->getAbsolutePath($srcPath);
        }

        $dstPath = $projectDirWriter->getAbsolutePath($dstPathInProject);
        $dstDir = dirname($dstPath);

        if ($projectDirWriter->isFile($dstDir)) {
            throw new \Exception("I expected '{$dstDir}' to be directory but I've found a file there");
        }

        $projectDirWriter->create($dstDir);

        $path = $this->creatuity()->resources()->fileAbsPath($srcPath, true);
        $projectDirWriter->copyFile($path, $dstPath);

        $srcPathTxt = $projectDirWriter->getRelativePath($srcPath);
        $dstPathTxt = $projectDirWriter->getRelativePath($dstPath);

        $this->creatuity()->report()->printSuccess("Copied '{$srcPathTxt}' to '{$dstPathTxt}' ");
    }

    public function removeFile($file, $allowDirsRemoval = false)
    {
        $this->removeFiles([$file], $allowDirsRemoval);
    }

    public function removeFiles($files, $allowDirsRemoval = false)
    {
        $isOk = true;

        $projectDir = $this->creatuity()->resources()->projectDirWriter();

        foreach ($files as $file) {
            try{
                if (!$projectDir->isExist($file)) {
                    $this->creatuity()->report()->printWarning("Cannot find '{$projectDir->getAbsolutePath($file)}'. Skipping.");
                    continue;
                }

                if (!$allowDirsRemoval && $projectDir->isDirectory($file)) {
                    throw new \Exception("Cannot remove '$file' directory. You must 'allowDirsRemoval' if You want to remove it.'");
                }

                $projectDir->delete($file);
                $this->creatuity()->report()->printSuccess("Removed '${file}'");
            }catch (\Exception $e) {
                $isOk = false;
                $this->creatuity()->report()->printError("Cannot remove '$file': " . $e->getMessage(), $e);
            }
        }

        return $isOk;
    }

    protected function determineSrcPath($srcPathInModule)
    {
        try{
            return $this->creatuity()->resources()->fileRelPath(
                $this->filesDir . '/' . $srcPathInModule);
        } catch ( ResourcesHelperException $e ) {
            return $this->creatuity()->resources()->fileRelPath($srcPathInModule);
        }
    }

    /**
     * @return Creatuity
     */
    protected function creatuity()
    {
        return $this->creatuity;
    }
}
