<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Model\Catalog\CategoriesModifier\Examples\InstallSimpleTreeExample;
use Creatuity\Base\Model\Catalog\CategoriesModifier\Examples\InstallTreeWithCustomRootCategoriesExample;
use Creatuity\Base\Model\Catalog\CategoriesModifier\Examples\MultistoreExample;
use Creatuity\Base\Model\Catalog\CategoriesModifier\Examples\SimpleCategoriesModificationExample;
use Creatuity\Base\Model\CsvParser\UtilityInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
final class CreatuityDemo extends SubjectAbstract
{
    public function __construct(Creatuity $creatuity)
    {
        throw new \Exception('"Demo" subject is only for code presentation. Execution is forbidden');
    }

    public function demoCms()
    {
        // * P A G E S *

        // saves [module]/data/cms/pages/enable-cookies.html file to 'enable-cookies' cms page body
        // however, ensure this id is unique. Otherwise first page from collection will be obtain
        $this->creatuity()->cms()->pageSave('enable-cookies');

        // save page assigned to particular store
        $this->creatuity()->cms()->pageSave('enable-cookies', ['from_store' => 2]);

        // saves [module]/data/cms/pages/enable-cookies.html file to 'enable-cookies' cms page body
        // and assigns it to stores with ids 1 & 2
        $this->creatuity()->cms()->pageSave('enable-cookies', [
            'stores' => [ 2, 3 ],
        ]);

        // assign page, currently assigned to some store, to different store
        $this->creatuity()->cms()->pageSave('enable-cookies', [
            'from_store' => 2,
            'stores' => [ 4 ],
        ]);

        /*
         assuming that you have [module]/data/cms/pages/contact_us.json file with content:
         {
            "stores" : [1, 2],
            "title" : "Private Sales title"
         }
         saves [module]/data/cms/pages/contact_us.html file to 'contact_us' cms page
         and assigns it to stores with ids 1 & 2, and also sets title to "Private Sales title"
        */
        $this->creatuity()->cms()->pageSave('contact_us');

        // For more advanced needs like titles translations
        // you can preload cms model and operate on it, if needed
        // However be sure that page by certain id, assigned to particular store exists
        $this->creatuity()->cms()->pageInstance('contact_us', 2)
            ->setTitle("English Title")
            ->save();

        $this->creatuity()->cms()->pageInstance('contact_us', 4)
            ->setTitle("Título español")
            ->save();

        // guess by yourself ;p
        $this->creatuity()->cms()->pageDelete('contact_us');

        // save page without processing content - f.e. do not substitute block identifiers with their database ids
        // content processing will be disabled for this function call only, future calls will have default state of content processing (enabled)
        $this->creatuity()
            ->cms()
            ->disableContentProcessing()
            ->pageSave('contact_us');

        // * B L O C K S *

        // for blocks we have similar API
        $this->creatuity()->cms()->blockSave('identifier');
        $this->creatuity()->cms()->blockDelete('identifier');
        $this->creatuity()->cms()->blockInstance('identifier');
    }

    public function demoCreatuityLogo()
    {
        $output = null; // OutputInterface

        // our company logo will be drawn
        $this->creatuity()->creatuityLogo()->writeCreatuityLogo($output);
    }

    public function demoCsv()
    {
        $allRows = [];

        // want to parse small [module|project|root]/data/csv/path_to_csv_file.csv ?
        // It's as easy as:
        $rowsArray = $this->creatuity()->csv()
            ->parse('test.csv')
            ->run()
        ;

        // or

        foreach ( $this->creatuity()->csv()->parse('test.csv')->run() as $row ) {
            // do something with row
        }

        $this->creatuity()->csv()
            ->parse('path_to_csv_file.csv')
            ->applyChunkLogic(function(array $rowData, UtilityInterface $utility) {
                // do something on chunk begin
                foreach ( $rowData as $row ) {
                    // do row logic
                }
                // do something on chunk end
            })->chunkSize(1000)
            ->run();

        // We can perform more advanced things:
        $this->creatuity()->csv()
            ->parse('path_to_csv_file.csv')
            ->withHeader(true)
            ->withTrimmingValues(true)
            ->columnSeparator(';')
            ->enclosure('"')
            ->escapeChar('\\')
            ->calculateTotalRowsNumBeforeProcessing(true)
            ->showProgress()
            ->applyLogic(function(array $rowData, UtilityInterface $utility) use (&$allRows) {
                $allRows[] = $rowData;
                $message = "Row count: " . $utility->rowCount();
                if (someConditionOn($rowData)) {
                    $utility->stop();
                }
            })
            ->run()
        ;

        // For really big and nasty csv files, probably the best way is to delegate it dedicated class
        // per line
        $this->creatuity()->csv()
            ->parse('path_to_csv_file.csv')
            ->applyLogic(MyClassImplementingLogicInterface::class)
            ->run()
        ;

        // per chunk
        $this->creatuity()->csv()
            ->parse('path_to_csv_file.csv')
            ->showProgress()
            ->applyChunkLogic(MyClassImplementingChunkLogicInterface::class)
            ->chunkSize(2000)
            ->run();
    }

    public function demoDatabase()
    {
        // quick way to operate on database
        $sql = $this->creatuity()->database()->connection()->select()
            ->from('table')
            ->where('something')
        ;
        $results = $this->creatuity()->database()->connection()->fetchAll($sql);

        // running simple (potentially dynamic) query
        $this->creatuity()->database()->runSql("UPDATE table WHERE something");

        // running bigger query from "[module]/data/sql/bigger_query.sql"
        $this->creatuity()->database()->runSqlFile('bigger_query');

        // turns an array from the argument into
        // [
        //    [ 'sex' => 'm'  , 'age' => null ],
        //    [ 'sex' => null , 'age' => 21  ],
        //    [ 'sex' => 'f'  , 'age' => 25 ],
        // ]
        $imReadyToBeInserted = $this->creatuity()->database()->normalizeDataSetForMultipleInsert([
            ['sex' => 'm'],
            ['age' => 21],
            ['sex' => 'f', 'age' => 25],
        ]);

        // we can run things in transaction
        $this->creatuity()->database()->runInTransaction(function() {
            // put sql statements here. They will be rolled back if error occurs
        });
    }

    public function demoEmulate()
    {
        $this->creatuity()->emulate()->runInFrontendArea(function () {
            // sometimes we need to run something in front-end scope
        });

        $this->creatuity()->emulate()->runInSecuredArea(function () {
            // sometimes we need to run something in backend scope
        });

        $this->creatuity()->emulate()->runWithConfig('somepath/to/config', 'test', function() {
            // do something, using configuration 'somepath/to/config' => 'test' at the time of callable execution
            // when callable finishes, changed configuration will be rolled back
        }, ScopeInterface::SCOPE_STORE, 'main_website');

        $this->creatuity()->emulate()->runWithConfigMany([
            'somepath/to/config' => 'test',
            'other/config/path' => 'super',
        ], function() {
            // do something, using changed values in several config paths at the time of callable execution
            // when callable finishes, changed configuration will be rolled back
        });
    }

    public function demoIndexer()
    {
        $this->creatuity()->indexer()->reindexAll();
        $this->creatuity()->indexer()->reindexAllInvalid();
    }

    public function demoReport()
    {
        $this->creatuity()->report()->printMessage("It's just a kind of 'echo'");
        $this->creatuity()->report()->printSuccess("This message will be printed in GREEN");
        $this->creatuity()->report()->printWarning("This message will be printed in ORANGE");
        $this->creatuity()->report()->printError("Error will be printer in RED.");

        // Separator. Smart enough to not put two separators in a row
        $this->creatuity()->report()->printLine();
    }

    public function demoResources()
    {
        // in all below examples 'path/to/file' can be a path
        //    - either relative to the module (where your script is),
        //    - either relative to the project,
        //    - either absolute on given workstation

        // is file exists ?
        $this->creatuity()->resources()->isExists('/path/to/file');


        // want to read it?
        $content = $this->creatuity()->resources()->fileRead('path/to/file');

        // is it json file? I will validate it for you
        $jsonContent = $this->creatuity()->resources()->jsonRead('/path/to/file.json');


        // or maybe you want to read/write just an arbitrary file at the workstation ?
        $etcHostsContent = $this->creatuity()->resources()->absoluteDirReader('/path/to/')->readFile('some/file');
        $this->creatuity()->resources()->absoluteDirWriter()->writeFile('/write/to/abs/path', 'content');


        // or maybe want to determine absolute path but you have only relative one ?
        $absPath = $this->creatuity()->resources()->fileAbsPath('path/to/file');
        // ... or vice-versa, have an absolute path, but you need relative one ?
        $relativePathToYourModule = $this->creatuity()->resources()->fileRelPath(
            '/some/absolute/path/to/file/in/our/module',
            $this->creatuity()->resources()->moduleDirReader());


        // if you will ever want to read line by line, then you can go like this
        foreach ($this->creatuity()->resources()->fileReadLines('/path/to/file') as $line) {
            // do something with trimmed $line
        }


        // want to read/write files but having a relative path only (relative to the project) ?
        $this->creatuity()->resources()->projectDirWriter()->writeFile('var/log/creatuity.log', 'content');
        $content = $this->creatuity()->resources()->projectDirReader()->readFile('pub/media/image.png');


        // want to operate on your module's files? That's easy:
        $content = $this->creatuity()->resources()->moduleDirReader()->readFile('data/my_module.file');
    }

    public function demoSeo()
    {
        // convert 'Lets convert  this' to 'lets-convert-this'
        $convertedWord = $this->creatuity()->seo()->nameToSeoUrlKey('Lets convert  this');
    }

    public function demoSettings()
    {
        // default scope
        $this->creatuity()->setting()->save('key', 'value');
        $this->creatuity()->setting()->delete('key');

        // variant scopes
        $this->creatuity()->setting()->settingsForWebsite('website_code')->save('key', 'value');
        $this->creatuity()->setting()->settingsForStoreGroup('store_group_code')->save('key', 'value');
        $this->creatuity()->setting()->settingsForStore('store_code')->save('key', 'value');

        // mass settings
        $this->creatuity()->setting()->settingsForStore('store_code')->saveMany([
            'key1' => 'value1',
            'key2' => 'value2',
        ]);
        $this->creatuity()->settings()->deleteMany([ 'key1', 'key2', 'key3' ]);

        // processing
        $value = $this->creatuity()->setting()->load('key');
        $this->creatuity()->setting()->save('key', strtoupper($value));
    }

    public function demoStore()
    {
        // Very nice, self-explain way of creating stores in definition-in-array way.
        $this->creatuity()->store()->setupExampleStores();

        // Set name for the single-store project
        $this->creatuity()->store()->defaultWebsiteModel()->setName("New Name Of The Website")->save();

        // want to add to store to existing group?
        $this->creatuity()->store()->addStoreView("French Store", "french_store",
            "existing_website_code", "Existing Store Group Name");

        // want to add new group with new store basing on the default root category?
        $this->creatuity()->store()->addStoreGroup("New Store Group",
            "New Store View", "new_store_view",
            $this->creatuity()->store()->defaultWebsiteModel()->getWebsiteId());

        // Let's add some totally new website group and view in fresh store...
        $this->creatuity()->store()->addWebsite(
            "New Website", "new_website",
            "New Store Group",
            "New Store View Name", "new_store_view_cdode");

        // Let's add a new store group with view basing on existing one...
        $existingGroup = $this->creatuity()->store()->storeGroupModel('Name Of Existing Website');
        $this->creatuity()->store()->addStoreGroup("New Store Group",
            "New Store View", "new_store_view",
            "existing_website_code", $existingGroup->getRootCategoryId());
    }

    public function demoTheme()
    {
        // When front-end developer creates an theme, he needs to assign it to given stores/websites
        $this->creatuity()->theme()->assignThemeToStore('theme_code_for_french_store', ['store_french'], 'store');
        // ... or make it a default one
        $this->creatuity()->theme()->assignThemeToDefaultStore('theme_code');
    }
}
