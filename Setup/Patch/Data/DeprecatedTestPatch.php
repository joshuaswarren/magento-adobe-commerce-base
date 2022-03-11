<?php

namespace Creatuity\Base\Setup\Patch\Data;

use Creatuity\Base\Helpers\Creatuity\Subjects\Exception\ModuleNotSetException;
use Creatuity\Base\Setup\AbstractDataPatch;
use Exception;

/**
 * @deprecated
 *
 * This Data Patch is intended to test compatibility with old way of using Creatuity Base.
 * Please do not use this one anymore
 * @see Creatuity\Base\Setup\Patch\Data\TestPatch
 */
class DeprecatedTestPatch extends AbstractDataPatch
{
    /**
     * @throws ModuleNotSetException
     * @throws Exception
     */
    protected function applyPatch(): self
    {
        $this->creatuity()->report()->printMessage('Deprecated Test Patch');

        /** Cms Subject Test */
        $this->creatuity()->cms()->blockSave('test-deprecated-block');
        $this->creatuity()->cms()->pageSave('test-deprecated-page');

        /** Csv Subject Test */
        foreach ($this->creatuity()->csv()->parse('test.csv')->run() as $row) {
            $this->creatuity()->report()->printMessage('CSV New Line');
            foreach ($row as $column => $value) {
                $this->creatuity()->report()->printMessage('   Column: ' . $column . ' Value: ' . $value);
            }
        }

        /** Database Subject Test */
        $sqlQueryResult = $this->creatuity()->database()->dbConnection()->fetchOne('SELECT value FROM core_config_data WHERE path = \'catalog/search/engine\'');
        $this->creatuity()->report()->printSuccess('Database Search Engine: ' . $sqlQueryResult);

        /** Emulate Subject Test */
        $reportUtility = $this->creatuity()->report();
        $this->creatuity()->emulate()->runInSecuredArea(function () use ($reportUtility) {
            $reportUtility->printSuccess('Ran inside runInSecuredArea method');
        });

        /** FilesInstaller Subject Test */
        $this->creatuity()->filesInstaller()->installByDirs([
            'pub/media/wysiwyg' => [
                'test-deprecated-img.png',
            ],
        ]);

        /** Indexer Subject Test */
        $this->creatuity()->indexer()->reindexCustomerGrid();

        /** Logo Subject Test */
        $this->creatuity()->creatuityLogo()->writeCreatuityLogo();

        /** Processing Subject Test */
        $toProcess = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
        $batches = $this->creatuity()->processing()->inChunk($toProcess,3);

        $this->creatuity()->report()->printMessage('Numbers to process: ' . implode(', ', $toProcess));
        foreach ($batches as $batch) {
            $this->creatuity()->report()->printMessage('Processing batch: ' . implode(', ', $batch));
        }

        /** Report Subject Test */
        $this->creatuity()->report()->printMessage('Creating test deprecated page and block...');

        /** Resources Subject Test */
        /** todo: prepare */

        /** Seo Subject Test */
        $difficultUrl = '**Super Promotion**';
        $sanitizedUrl = $this->creatuity()->seo()->nameToSeoUrlKey($difficultUrl);
        $this->creatuity()->report()->printMessage('Seo processing: ' . $difficultUrl . ' => ' . $sanitizedUrl);

        /** Setting Subject Test */
        $searchEngineConfig = $this->creatuity()->setting()->load('catalog/search/engine');
        $this->creatuity()->report()->printSuccess('Database Search Engine: ' . $searchEngineConfig);

        /** Store Subject Test */
        $store = $this->creatuity()->store()->storeViewModel(1);
        $this->creatuity()->report()->printMessage('Name of the store "1": ' . $store->getName());

        /** Theme Subject Test */
        $this->creatuity()->theme()->assignThemeToDefaultStore('notExistentThemeCode');

        return $this;
    }

    public static function getDependencies(): array
    {
        return [];
    }
}

