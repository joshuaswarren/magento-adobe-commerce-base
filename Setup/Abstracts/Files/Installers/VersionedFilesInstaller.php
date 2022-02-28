<?php

namespace Creatuity\Base\Setup\Abstracts\Files\Installers;

/**
 * @package dlc
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
class VersionedFilesInstaller extends FilesInstaller
{
    /**
     * @var array
     */
    protected $installByFilesInvokes = [];

    /**
     * @var array
     */
    protected $installByDirsInvokes = [];

    /**
     * @var array
     */
    protected $removeFilesInvokes = [];

    /**
     * @var bool
     */
    protected $capturingEnabled = false;

    /**
     * @var string
     */
    protected $capturingVersion = '';

    /**
     * @var array
     */
    protected $newVersions = [];

    /**
     * @var array
     */
    protected $errorVersions = [];

    /**
     * @var bool
     */
    protected $allMustBeCorrectByDirs = true;

    /**
     * @var bool
     */
    protected $allMustBeCorrectByFiles = true;

    /**
     * @var bool
     */
    protected $allMustBeCorrectRemoved = false;


    public function setup($allMustBeCorrectByDirs, $allMustBeCorrectByFiles, $allMustBeCorrectRemoved)
    {
        $this->allMustBeCorrectRemoved = $allMustBeCorrectByDirs;
        $this->allMustBeCorrectByFiles = $allMustBeCorrectByFiles;
        $this->allMustBeCorrectRemoved = $allMustBeCorrectRemoved;
    }

    public function clean()
    {
        $this->capturingEnabled = false;
        $this->capturingVersion = '';
        $this->installByDirsInvokes = [];
        $this->installByFilesInvokes = [];
        $this->removeFilesInvokes = [];
        $this->newVersions = [];
        $this->errorVersions = [];
    }

    public function enableCapturing($version)
    {
        $this->capturingEnabled = true;
        $this->capturingVersion = $version;
        $this->installByDirsInvokes[ $version ] = [];
        $this->installByFilesInvokes[ $version ] = [];
        $this->removeFilesInvokes[ $version ] = [];
    }

    public function installByDirs(array $dirsWithFiles)
    {
        if (!$this->capturingEnabled) {
            parent::installByDirs($dirsWithFiles);
        }

        $normalizedDirsWithFiles = [];
        foreach ($dirsWithFiles as $dir => $value) {
            $normalizedDirsWithFiles[ $this->dir($dir) ] = $value;
        }
        $this->installByDirsInvokes[ $this->capturingVersion ][] = $normalizedDirsWithFiles;
    }

    public function installByFiles(array $filesToDirs)
    {
        if (!$this->capturingEnabled) {
            parent::installByFiles($filesToDirs);
        }

        $normalizedFilesToDir = '';
        foreach ($filesToDirs as $file => $dirs) {
            $normalizedFilesToDir[ $file ] = array_map([$this, 'dir'], (array)$dirs);
        }
        $this->installByFilesInvokes[ $this->capturingVersion ][] = $filesToDirs;
    }

    public function removeFiles($files, $allowDirsRemoval = false)
    {
        if (!$this->capturingEnabled) {
            parent::removeFiles($files, $allowDirsRemoval);
        }

        $this->removeFilesInvokes[ $this->capturingVersion ][] = [(array)$files, $allowDirsRemoval];
    }

    public function allCapturedDestinationPaths()
    {
        return array_keys([]
            + $this->allCapturedDstPathsByDirs()
            + $this->allCapturedDstPathsByFiles()
            + $this->allCapturedDstPathsForRemovals()
        );
    }

    protected function allCapturedDstPathsByDirs()
    {
        $paths = [];
        foreach ($this->installByDirsInvokes as $version => $invokes) {
            foreach ($invokes as $dirsWithFiles) {
                foreach ($dirsWithFiles as $path => $dummy) {
                    $paths[ $this->dir($path) ] = true;
                }
            }
        }

        return $paths;
    }

    protected function allCapturedDstPathsByFiles()
    {
        $paths = [];
        foreach ($this->installByFilesInvokes as $version => $invokes) {
            foreach ($invokes as $filesToDirs) {
                foreach ($filesToDirs as $file => $dirs) {
                    foreach ((array)$dirs as $dir) {
                        $paths[ $this->dir($dir) ] = true;
                    }
                }
            }
        }

        return $paths;
    }

    protected function allCapturedDstPathsForRemovals()
    {
        $paths = [];
        foreach ($this->removeFilesInvokes as $version => $invokes) {
            foreach ($invokes as $args) {
                list ($files, $allowDirs) = $args;
                foreach ($files as $file) {
                    $paths[ $this->dir(dirname($file)) ] = true;
                }
            }
        }

        return $paths;
    }

    public function flushCapturedForSingle($currentVersion)
    {
        $versionCandidates = [];
        $versionsWithErrors = [];

        $this->sortInvokesByVersion();

        $this->creatuity()->report()->ensureNextOutputWillBeSeparated(2);

        foreach ($this->installByDirsInvokes as $version => $invokes) {
            foreach ($invokes as $dirsWithFiles) {
                if (!$currentVersion || version_compare($currentVersion, $version) < 0) {
                    if ($this->verboseInstallByDirs($dirsWithFiles, $version) || !$this->allMustBeCorrectByDirs) {
                        $versionCandidates[ $version ] = $version;
                    } else {
                        $versionsWithErrors[ $version ] = $version;
                    }
                }
            }
        }

        foreach ($this->installByFilesInvokes as $version => $invokes) {
            foreach ($invokes as $filesToDirs) {
                if (!$currentVersion || version_compare($currentVersion, $version) < 0) {
                    if ($this->verboseInstallByFiles($filesToDirs, $version) || !$this->allMustBeCorrectByFiles) {
                        $versionCandidates[ $version ] = $version;
                    } else {
                        $versionsWithErrors[ $version ] = $version;
                    }
                }
            }
        }

        foreach ($this->removeFilesInvokes as $version => $invokes) {
            foreach ($invokes as $params) {
                list($files, $allowDirs) = $params;

                if (!$currentVersion || version_compare($currentVersion, $version) < 0) {
                    if ($this->verboseRemoveFile($version, $files, $allowDirs) || !$this->allMustBeCorrectRemoved) {
                        $versionCandidates[ $version ] = $version;
                    } else {
                        $versionsWithErrors[ $version ] = $version;
                    }
                }
            }
        }

        $this->clean();


        $newVersions = array_diff_key($versionCandidates, $versionsWithErrors);
        if (empty($newVersions)) {
            return $currentVersion;
        }

        $versionCandidates = array_values($newVersions);
        usort($versionCandidates, 'version_compare');

        return $versionCandidates[ count($versionCandidates) - 1 ];
    }

    public function flushCapturedForMany(array $alreadyInstalledVersions)
    {
        $this->newVersions = [];

        $this->sortInvokesByVersion();

        $this->creatuity()->report()->ensureNextOutputWillBeSeparated(2);

        $this->flushCapturedByDirs($alreadyInstalledVersions);

        $this->flushCapturedByFiles($alreadyInstalledVersions);

        $this->flushCapturedRemovedFiles($alreadyInstalledVersions);

        $newVersions = $this->newVersions();

        $this->clean();

        return $newVersions;
    }

    protected function flushCapturedByDirs(array $alreadyInstalledVersions)
    {
        $newVersions = [];
        foreach ($this->installByDirsInvokes as $version => $invokes) {
            foreach ($invokes as $dirsWithFiles) {
                $this->flushInvocationCapturedByDirs($alreadyInstalledVersions, $dirsWithFiles, $version);
            }
        }

        return $newVersions;
    }

    protected function flushInvocationCapturedByDirs(array $alreadyInstalledVersions, $dirsWithFiles, $version)
    {
        foreach ($dirsWithFiles as $dstDir => $files) {
            $dstDir = $this->dir($dstDir);

            if (empty($alreadyInstalledVersions[ $dstDir ])
                || version_compare($alreadyInstalledVersions[ $dstDir ], $version) < 0
            ) {
                if ($this->verboseInstallByDirs([$dstDir => $files], $version) || !$this->allMustBeCorrectByDirs) {
                    $this->addNewVersion($dstDir, $version);
                } else {
                    $this->markErrorForVersion($dstDir, $version);
                }
            }
        }
    }

    protected function flushCapturedByFiles(array $alreadyInstalledVersions)
    {
        $newVersions = [];
        foreach ($this->installByFilesInvokes as $version => $invokes) {
            foreach ($invokes as $filesToDirs) {
                $this->flushInvocationCapturedByFiles($alreadyInstalledVersions, $filesToDirs, $version);
            }
        }

        return $newVersions;
    }

    protected function flushInvocationCapturedByFiles(array $alreadyInstalledVersions, $filesToDirs, $version)
    {
        foreach ($filesToDirs as $file => &$dstDirs) {
            $dstDirs = (array)$dstDirs;
            foreach ($dstDirs as $key => $dstDir) {
                $dstDir = $this->dir($dstDir);

                if (empty($alreadyInstalledVersions[ $dstDir ])
                    || version_compare($alreadyInstalledVersions[ $dstDir ], $version) < 0
                ) {
                    if ($this->verboseInstallByFiles($filesToDirs, $version) || !$this->allMustBeCorrectByFiles) {
                        $this->addNewVersion($dstDir, $version);
                    } else {
                        $this->markErrorForVersion($dstDir, $version);
                    }
                }
            }
        }

    }

    protected function flushCapturedRemovedFiles(array $alreadyInstalledVersions)
    {
        $newVersions = [];
        foreach ($this->removeFilesInvokes as $version => $invokes) {
            foreach ($invokes as $params) {
                $this->flushInvocationCapturedByRemoveFiles($alreadyInstalledVersions, $params, $version);
            }
        }

        return $newVersions;
    }

    protected function flushInvocationCapturedByRemoveFiles(array $alreadyInstalledVersions, $params, $version)
    {
        list($files, $allowDirs) = $params;

        foreach ($files as $k => $file) {
            $dstDir = $this->dir(\dirname($file));

            if (empty($alreadyInstalledVersions[ $dstDir ])
                || version_compare($alreadyInstalledVersions[ $dstDir ], $version) < 0
            ) {
                if ($this->verboseRemoveFile($version, $files, $allowDirs) || !$this->allMustBeCorrectRemoved) {
                    $this->addNewVersion($dstDir, $version);
                } else {
                    $this->markErrorForVersion($dstDir, $version);
                }
            }
        }

    }

    protected function verboseInstallByDirs($dirsWithFiles, $version)
    {
        $this->creatuity()->report()->printLine('=');
        $this->creatuity()->report()->printMessage(sprintf("%s::%s", $this->className, $version));
        $this->creatuity()->report()->printLine('-');

        $ret = parent::installByDirs($dirsWithFiles);

        $this->creatuity()->report()->printLine('=');

        return $ret;
    }

    protected function verboseInstallByFiles($filesToDirs, $version)
    {
        $this->creatuity()->report()->printLine('=');
        $this->creatuity()->report()->printMessage(sprintf("%s::%s", $this->className, $version));
        $this->creatuity()->report()->printLine('-');

        $ret = parent::installByFiles($filesToDirs);

        $this->creatuity()->report()->printLine('=');

        return $ret;
    }

    protected function verboseRemoveFile($version, $files, $allowDirs)
    {
        $this->creatuity()->report()->printLine('=');
        $this->creatuity()->report()->printMessage(sprintf("%s::%s", $this->className, $version));
        $this->creatuity()->report()->printLine('-');

        $ret = parent::removeFiles($files, $allowDirs);

        $this->creatuity()->report()->printLine('=');

        return $ret;
    }

    protected function addNewVersion($dstDir, $version)
    {
        $dir = $this->dir($dstDir);

        if (!isset($this->newVersions[ $dir ])) {
            $this->newVersions[ $dir ] = [];
        }
        $this->newVersions[ $dir ][ $version ] = $version;
    }

    protected function markErrorForVersion($dstDir, $version)
    {
        $dir = $this->dir($dstDir);

        if (!isset($this->errorVersions[ $dir ])) {
            $this->errorVersions[ $dir ] = [];
        }
        $this->errorVersions[ $dir ][ $version ] = $version;
    }

    protected function newVersions()
    {
        $ret = [];

        foreach($this->newVersions as $dir => $versions) {
            $dir = $this->dir($dir);

            $confirmedVersions = array_values(array_diff_key($versions, (array)@$this->errorVersions[ $dir ]));
            if (empty($confirmedVersions)) {
                continue;
            }

            usort($confirmedVersions, 'version_compare');

            $ret[$dir] = $confirmedVersions[ count($confirmedVersions) - 1 ];
        }

        return $ret;
    }

    protected function dir($dir)
    {
        return trim($dir, '/');
    }

    protected function sortInvokesByVersion()
    {
        uksort($this->installByFilesInvokes, 'version_compare');
        uksort($this->installByDirsInvokes, 'version_compare');
        uksort($this->removeFilesInvokes, 'version_compare');
    }
}