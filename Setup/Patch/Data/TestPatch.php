<?php

namespace Creatuity\Base\Setup\Patch\Data;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Subjects\Cms as CmsUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Database as DatabaseUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Emulate as EmulateUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\FilesInstaller as FilesInstallerUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Indexer as IndexerUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Processing as ProcessingUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Report as ReportUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Seo as SeoUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Setting as SettingUtility;
use Exception;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class TestPatch implements DataPatchInterface
{
    private CmsUtility $cmsUtility;
    private ReportUtility $reportUtility;
    private FilesInstallerUtility $filesInstaller;
    private DatabaseUtility $databaseUtility;
    private EmulateUtility $emulateUtility;
    private IndexerUtility $indexerUtility;
    private ProcessingUtility $processingUtility;
    private SeoUtility $seoUtility;
    private SettingUtility $settingUtility;

    public function __construct(
        CmsUtility $cmsUtility,
        ReportUtility $reportUtility,
        FilesInstallerUtility $filesInstaller,
        DatabaseUtility $databaseUtility,
        EmulateUtility $emulateUtility,
        IndexerUtility $indexerUtility,
        ProcessingUtility $processingUtility,
        SeoUtility $seoUtility,
        SettingUtility $settingUtility
    ) {
        $this->cmsUtility = $cmsUtility;
        $this->reportUtility = $reportUtility;
        $this->filesInstaller = $filesInstaller;
        $this->databaseUtility = $databaseUtility;
        $this->emulateUtility = $emulateUtility;
        $this->indexerUtility = $indexerUtility;
        $this->processingUtility = $processingUtility;
        $this->seoUtility = $seoUtility;
        $this->settingUtility = $settingUtility;
    }

    /**
     * @throws Creatuity\Subjects\Exception\ModuleNotSetException
     * @throws Exception
     */
    public function apply(): self
    {
        /** Report Subject Test */
        $this->reportUtility->printMessage('Creating test page and block...');

        /** Cms Subject Test */
        $this->cmsUtility->forModule('Creatuity_Base');
        $this->cmsUtility->blockSave('test-block');
        $this->cmsUtility->pageSave('test-page');

        /** FilesInstaller Subject Test */
        $this->filesInstaller->forModule('Creatuity_Base');
        $this->filesInstaller->installByDirs([
           'pub/media/wysiwyg' => [
               'test-img.png',
           ],
        ]);

        /** Database Subject Test */
        $sqlQueryResult = $this->databaseUtility->dbConnection()->fetchOne('SELECT value FROM core_config_data WHERE path = \'catalog/search/engine\'');
        $this->reportUtility->printSuccess('Database Search Engine: ' . $sqlQueryResult);

        /** Emulate Subject Test */
        $reportUtility = $this->reportUtility;
        $this->emulateUtility->runInSecuredArea(function () use ($reportUtility) {
            $reportUtility->printSuccess('Ran inside runInSecuredArea method');
        });

        /** Indexer Subject Test */
        $this->indexerUtility->reindexCustomerGrid();

        /** Processing Subject Test */
        $toProcess = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
        $batches = $this->processingUtility->inChunk($toProcess,3);

        $this->reportUtility->printMessage('Numbers to process: ' . implode(', ', $toProcess));
        foreach ($batches as $batch) {
            $this->reportUtility->printMessage('Processing batch: ' . implode(', ', $batch));
        }

        /** Seo Subject Test */
        $difficultUrl = '**Super Promotion**';
        $sanitizedUrl = $this->seoUtility->nameToSeoUrlKey($difficultUrl);
        $this->reportUtility->printMessage('Seo processing: ' . $difficultUrl . ' => ' . $sanitizedUrl);

        /** Setting Subject Test */
        $searchEngineConfig = $this->settingUtility->load('catalog/search/engine');
        $this->reportUtility->printSuccess('Database Search Engine: ' . $searchEngineConfig);

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

