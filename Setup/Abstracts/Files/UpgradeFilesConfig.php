<?php

namespace Creatuity\Base\Setup\Abstracts\Files;

/**
 * @package dlc
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
class UpgradeFilesConfig
{

    /**
     * @var bool
     */
    protected $useManyDeployFiles = true;

    /**
     * @var string
     */
    protected $fallbackDeployFilePath = '/';

    /**
     * @var string
     */
    protected $singleDeployFilePath = '/app/etc/';

    /**
     * @var string
     */
    protected $markerFilename = 'creatuity.deploy';

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

    /**
     * @return string
     */
    public function getFallbackDeployFilePath()
    {
        return $this->fallbackDeployFilePath;
    }

    /**
     * @return bool
     */
    public function isUseManyDeployFiles()
    {
        return $this->useManyDeployFiles;
    }

    /**
     * @return string
     */
    public function getMarkerFilename()
    {
        return $this->markerFilename;
    }

    /**
     * @return string
     */
    public function getSingleDeployFilePath()
    {
        return $this->singleDeployFilePath;
    }

    /**
     * @return bool
     */
    public function isAllMustBeCorrectByDirs()
    {
        return $this->allMustBeCorrectByDirs;
    }

    /**
     * @return bool
     */
    public function isAllMustBeCorrectByFiles()
    {
        return $this->allMustBeCorrectByFiles;
    }

    /**
     * @return bool
     */
    public function isAllMustBeCorrectRemoved()
    {
        return $this->allMustBeCorrectRemoved;
    }

}