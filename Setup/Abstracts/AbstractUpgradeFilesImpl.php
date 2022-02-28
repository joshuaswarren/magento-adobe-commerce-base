<?php

namespace Creatuity\Base\Setup\Abstracts;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Setup\Abstracts\Files\DecoratorForOurHelper;
use Creatuity\Base\Setup\Abstracts\Files\DecoratorForOurHelperException;
use Creatuity\Base\Setup\Abstracts\Files\UpgradeFilesConfig;
use Creatuity\Base\Setup\AbstractUpgradeData;
use Creatuity\Base\Setup\Abstracts\Files\Installers\FilesInstaller;
use Creatuity\Base\Setup\Abstracts\Files\Installers\VersionedFilesInstaller;
use Creatuity\Base\Setup\Abstracts\Files\Installers\VersionedFilesInstallerFactory;
use Creatuity\Base\Setup\Abstracts\ModuleContext\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @package ygy
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
abstract class AbstractUpgradeFilesImpl extends AbstractUpgradeData
{
    const SINGLE_DEPLOY_FILE = '~!/single-deploy-file/~!';
    const FALLBACK_DEPLOY_FILE = '~!/fallback-deploy-file/~!';

    /**
     * @var array[]
     */
    private $markersCache = [];

    /**
     * @var string
     */
    private $thisModuleName = '';

    /**
     * @var FilesInstaller
     */
    private $filesInstaller;

    /**
     * @var VersionedFilesInstallerFactory
     */
    private $versionedFilesInstallerFactory;

    /**
     * @var UpgradeFilesConfig
     */
    private $config;

    /**
     * @var Creatuity
     *
     * @deprecated use $this->creatuity() method
     */
    protected $creatuity;

    /**
     * @var Creatuity
     */
    private $creatuityPrivate;

    public function __construct(
        VersionedFilesInstallerFactory $versionedFilesInstallerFactory,
        UpgradeFilesConfig $config,
        AbstractUpgradeDataContext $context
    ) {
        parent::__construct($context);

        $this->thisModuleName = $context->getModuleNameResolver()->byObject($this);
        $this->config = $config;
        $this->creatuityPrivate = $context->getCreatuity()->forModule($this->thisModuleName);
        $this->creatuity = new DecoratorForOurHelper($this->creatuityPrivate);
        $this->versionedFilesInstallerFactory = $versionedFilesInstallerFactory;
    }

    protected function isVersionNeedsInstallation(ModuleContextInterface $context, $version)
    {
        // we're running all upgrade scripts,
        // as we're going to determine which of them haven't been installed yet on given Magento instance.
        return true;
    }

    protected function callUpgrade($version, $callback, array $args)
    {
        try {
            $this->versFilesInstaller()->enableCapturing($version);

            $ret = call_user_func_array($callback, $args);

            return $ret;
        } catch (\Exception $e) {
            $this->handleScriptFailure($callback, $e);
            // we're intentionally not re-throwing
        }
    }

    protected function doUpgrade($setup, ModuleContextInterface $context)
    {
        try {
            $this->versFilesInstaller()->setup(
                $this->config->isAllMustBeCorrectByDirs(),
                $this->config->isAllMustBeCorrectByFiles(),
                $this->config->isAllMustBeCorrectRemoved()
            );
            return $this->performVersionedUpgrade($setup, $context);
        } catch (\Exception $e) {
            $this->creatuityPrivate->report()->printError(sprintf(
                "Problem occurred during running file upgrade scripts for %s: %s",
                get_class($this), $e->getMessage()), $e);
        }
    }

    private function performVersionedUpgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        try {
            $this->versFilesInstaller()->clean();

            parent::doUpgrade($setup, $context);

        } catch (DecoratorForOurHelperException $e) {
            throw new \Exception("You cannot use any helper other than \$this->filesInstaller() and \$this->creatuity()->report() in classes extended from " . __CLASS__);
        } finally {
            if ($this->config->isUseManyDeployFiles()) {
                $allCapturedDstPaths = $this->versFilesInstaller()->allCapturedDestinationPaths();

                $alreadyInstalledVersions = $this->readVersions($allCapturedDstPaths);

                $newVersions = $this->versFilesInstaller()->flushCapturedForMany($alreadyInstalledVersions);

                $this->writeVersions($newVersions);
            } else {
                $versions = $this->readMarkerFile(self::SINGLE_DEPLOY_FILE);
                $currentVersion = !empty($versions[ $this->thisModuleName ])
                    ? $versions[ $this->thisModuleName ]
                    : null;

                $newVersion = $this->versFilesInstaller()->flushCapturedForSingle($currentVersion);
                if ($newVersion && $newVersion != $currentVersion) {
                    $this->writeMarkerFile(self::SINGLE_DEPLOY_FILE, $newVersion);
                }
            }
        }
    }

    /**
     * @return array
     */
    private function readVersions(array $dstPaths)
    {
        $fallbackContent = $this->readMarkerFile(self::FALLBACK_DEPLOY_FILE);

        $alreadyInstalledVersions = [];
        foreach ($dstPaths as $dstPath) {
            $markerContent = $this->readMarkerFile($dstPath);

            $alreadyInstalledVersions[ $dstPath ] = false;
            if (!empty($markerContent[ $this->thisModuleName ])) {
                $alreadyInstalledVersions[ $dstPath ] = $markerContent[ $this->thisModuleName ];
            } elseif (!empty($fallbackContent[ $this->thisModuleName ])) {
                $alreadyInstalledVersions[ $dstPath ] = $fallbackContent[ $this->thisModuleName ];
            }
        }

        return $alreadyInstalledVersions;
    }

    protected function writeVersions(array $versionsPerPath)
    {
        if (empty($versionsPerPath)) {
            return;
        }

        foreach ($versionsPerPath as $dstPath => $version) {
            try {
                $this->writeMarkerFile($dstPath, $version);
            } catch (\Exception $e) {
                $this->creatuityPrivate->report()->printError(
                    "I cannot mark {$this->thisModuleName}:{$version} in {$dstPath}/creatuity.deploy file: " . $e->getMessage(), $e);
            }
        }
    }

    private function readMarkerFile($projectDstPath)
    {
        if (!isset($this->markersCache[ $projectDstPath ])) {
            $this->markersCache[ $projectDstPath ] = $this->parseMarkerFile($projectDstPath);
        }

        return $this->markersCache[ $projectDstPath ];
    }

    private function writeMarkerFile($projectDstPath, $version)
    {
        $this->readMarkerFile($projectDstPath);

        $this->markersCache[ $projectDstPath ][ $this->thisModuleName ] = $version;

        $this->renderMarkerFile($projectDstPath, $this->markersCache[ $projectDstPath ]);
    }

    private function parseMarkerFile($projectDstPath)
    {
        $markerPath = $this->markerPath($projectDstPath);

        $dirReader = $this->creatuityPrivate->resources()->projectDirReader();
        if (!$dirReader->isExist($markerPath)) {
            return [];
        }

        $result = [];
        foreach ($this->creatuityPrivate->resources()->fileReadLines($markerPath) as $line) {
            if ($line[0] == '#') {
                continue;
            }
            list($module, $version) = explode('|', $line);
            $result[ $module ] = $version;
        }

        return $result;
    }

    private function renderMarkerFile($projectDstPath, array $content)
    {
        $markerPath = $this->markerPath($projectDstPath);

        $dirWriter = $this->creatuityPrivate->resources()->projectDirWriter();
        $writer = $dirWriter->openFile($markerPath);

        $writer->write(
            "#########################################################\n" .
            "#           CREATUITY FILES DEPLOY MARKER               #\n" .
            "#########################################################\n" .
            "# ------------------------------------------------------#\n" .
            "#                  ~ ! WARNING ! ~                      #\n" .
            "# ------------------------------------------------------#\n" .
            "#       ! Do Not Remove this technical file !           #\n" .
            "#    It's needed for proper deployment of our work,     #\n" .
            "#               without collisions                      #\n" .
            "#          with other's developers work.                #\n" .
            "#########################################################\n"
        );

        foreach ($content as $module => $version) {
            $writer->write("{$module}|{$version}\n");
        }

        $writer->write(
            "#########################################################\n"
        );

        $writer->close();
    }

    private function markerPath($projectDstPath)
    {
        if ($projectDstPath === self::FALLBACK_DEPLOY_FILE) {
            return $this->config->getFallbackDeployFilePath() . '/' . $this->config->getMarkerFilename();
        }

        if ($projectDstPath !== self::SINGLE_DEPLOY_FILE && $this->config->isUseManyDeployFiles()) {
            return $projectDstPath . '/' . $this->config->getMarkerFilename();
        } else {
            return $this->config->getSingleDeployFilePath() . '/' . $this->config->getMarkerFilename();
        }
    }

    /**
     * @return VersionedFilesInstaller
     */
    protected function versFilesInstaller()
    {
        return $this->filesInstaller();
    }

    /**
     * @return FilesInstaller
     */
    protected function filesInstaller()
    {
        if (!$this->filesInstaller) {
            $this->filesInstaller = $this->versionedFilesInstallerFactory->create([
                'creatuity' => $this->creatuityPrivate,
                'className' => get_class($this)
            ]);
        }

        return $this->filesInstaller;
    }

    /**
     * @return Creatuity
     */
    protected function creatuity()
    {
        return $this->creatuity;
    }
}