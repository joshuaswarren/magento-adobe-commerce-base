<?php

namespace Creatuity\Base\Setup\Patch\Data;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Subjects\Cms as CmsUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Report as ReportUtility;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class TestPatch implements DataPatchInterface
{
    private CmsUtility $cmsUtility;
    private ReportUtility $reportUtility;

    public function __construct(
        CmsUtility $cmsUtility,
        ReportUtility $reportUtility
    ) {
        $this->cmsUtility = $cmsUtility;
        $this->reportUtility = $reportUtility;
    }

    public function apply(): self
    {
        $this->reportUtility->printMessage('Creating test deprecated page and block...');

        $this->cmsUtility->blockSave('test-deprecated-block');
        $this->cmsUtility->pageSave('test-deprecated-page');

        return $this;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}

