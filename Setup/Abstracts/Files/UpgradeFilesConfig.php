<?php

namespace Creatuity\Base\Setup\Abstracts\Files;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class UpgradeFilesConfig
{
    private bool $useManyDeployFiles = true;
    private string $fallbackDeployFilePath = '/';
    private string $singleDeployFilePath = '/app/etc/';
    private string $markerFilename = 'creatuity.deploy';
    private bool $allMustBeCorrectByDirs = true;
    private bool $allMustBeCorrectByFiles = true;
    private bool $allMustBeCorrectRemoved = false;

    public function __construct(
        $useManyDeployFiles = true,
        $fallbackDeployFilePath = '/',
        $singleDeployFilePath = '/app/etc/',
        $markerFilename = 'creatuity.deploy',
        $allMustBeCorrectByDirs = true,
        $allMustBeCorrectByFiles = true,
        $allMustBeCorrectRemoved = false
    ) {
        $this->useManyDeployFiles = $useManyDeployFiles;
        $this->fallbackDeployFilePath = $fallbackDeployFilePath;
        $this->markerFilename = $markerFilename;
        $this->singleDeployFilePath = $singleDeployFilePath;
        $this->allMustBeCorrectByFiles = $allMustBeCorrectByFiles;
        $this->allMustBeCorrectByDirs = $allMustBeCorrectByDirs;
        $this->allMustBeCorrectRemoved = $allMustBeCorrectRemoved;
    }

    public function getFallbackDeployFilePath(): string
    {
        return $this->fallbackDeployFilePath;
    }

    public function isUseManyDeployFiles(): bool
    {
        return $this->useManyDeployFiles;
    }

    public function getMarkerFilename(): string
    {
        return $this->markerFilename;
    }

    public function getSingleDeployFilePath(): string
    {
        return $this->singleDeployFilePath;
    }

    public function isAllMustBeCorrectByDirs(): bool
    {
        return $this->allMustBeCorrectByDirs;
    }

    public function isAllMustBeCorrectByFiles(): bool
    {
        return $this->allMustBeCorrectByFiles;
    }

    public function isAllMustBeCorrectRemoved(): bool
    {
        return $this->allMustBeCorrectRemoved;
    }

}
