<?php

namespace Creatuity\Base\Setup\Patch\Data;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Subjects\Cms as CmsUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Database as DatabaseUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Emulate as EmulateUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\FilesInstaller as FilesInstallerUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Indexer as IndexerUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Logo as LogoUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Processing as ProcessingUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Report as ReportUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Seo as SeoUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Setting as SettingUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Store as StoreUtility;
use Creatuity\Base\Helpers\Creatuity\Subjects\Theme as ThemeUtility;
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
    private StoreUtility $storeUtility;
    private ThemeUtility $themeUtility;
    private LogoUtility $logoUtility;

    public function __construct(
        CmsUtility $cmsUtility,
        ReportUtility $reportUtility,
        FilesInstallerUtility $filesInstaller,
        DatabaseUtility $databaseUtility,
        EmulateUtility $emulateUtility,
        IndexerUtility $indexerUtility,
        ProcessingUtility $processingUtility,
        SeoUtility $seoUtility,
        SettingUtility $settingUtility,
        StoreUtility $storeUtility,
        ThemeUtility $themeUtility,
        LogoUtility $logoUtility
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
        $this->storeUtility = $storeUtility;
        $this->themeUtility = $themeUtility;
        $this->logoUtility = $logoUtility;
    }

    /**
     * @throws Creatuity\Subjects\Exception\ModuleNotSetException
     * @throws Exception
     */
    public function apply(): self
    {
        /** Cms Subject Test */
        $this->cmsUtility->forModule('Creatuity_Base');
        $this->cmsUtility->blockSave('test-block');
        $this->cmsUtility->pageSave('test-page');

        /** Csv Subject Test */
        /** todo: prepare */

        /** Database Subject Test */
        $sqlQueryResult = $this->databaseUtility->dbConnection()->fetchOne('SELECT value FROM core_config_data WHERE path = \'catalog/search/engine\'');
        $this->reportUtility->printSuccess('Database Search Engine: ' . $sqlQueryResult);

        /** Emulate Subject Test */
        $reportUtility = $this->reportUtility;
        $this->emulateUtility->runInSecuredArea(function () use ($reportUtility) {
            $reportUtility->printSuccess('Ran inside runInSecuredArea method');
        });

        /** FilesInstaller Subject Test */
        $this->filesInstaller->forModule('Creatuity_Base');
        $this->filesInstaller->installByDirs([
            'pub/media/wysiwyg' => [
                'test-img.png',
            ],
        ]);

        /** Indexer Subject Test */
        $this->indexerUtility->reindexCustomerGrid();

        /** Logo Subject Test */
        $this->logoUtility->writeCreatuityLogo();

        /** Processing Subject Test */
        $toProcess = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
        $batches = $this->processingUtility->inChunk($toProcess,3);

        $this->reportUtility->printMessage('Numbers to process: ' . implode(', ', $toProcess));
        foreach ($batches as $batch) {
            $this->reportUtility->printMessage('Processing batch: ' . implode(', ', $batch));
        }

        /** Report Subject Test */
        $this->reportUtility->printMessage('Creating test page and block...');

        /** Resources Subject Test */
        /** todo: prepare */

        /** Seo Subject Test */
        $difficultUrl = '**Super Promotion**';
        $sanitizedUrl = $this->seoUtility->nameToSeoUrlKey($difficultUrl);
        $this->reportUtility->printMessage('Seo processing: ' . $difficultUrl . ' => ' . $sanitizedUrl);

        /** Setting Subject Test */
        $searchEngineConfig = $this->settingUtility->load('catalog/search/engine');
        $this->reportUtility->printSuccess('Database Search Engine: ' . $searchEngineConfig);

        /** Store Subject Test */
        $store = $this->storeUtility->storeViewModel(1);
        $this->reportUtility->printMessage('Name of the store "1": ' . $store->getName());

        /** Theme Subject Test */
        $this->themeUtility->assignThemeToDefaultStore('notExistentThemeCode');

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

