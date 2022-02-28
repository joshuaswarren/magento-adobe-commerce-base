<?php
namespace Creatuity\Base\Helpers;

use Creatuity\Base\Helpers\Creatuity\Subjects\Catalog;
use Creatuity\Base\Helpers\Creatuity\Subjects\Cms;
use Creatuity\Base\Helpers\Creatuity\Subjects\CreatuityDemo;
use Creatuity\Base\Helpers\Creatuity\Subjects\Logo;
use Creatuity\Base\Helpers\Creatuity\Subjects\Database;
use Creatuity\Base\Helpers\Creatuity\Subjects\Emulate;
use Creatuity\Base\Helpers\Creatuity\Subjects\Indexer;
use Creatuity\Base\Helpers\Creatuity\Subjects\Processing;
use Creatuity\Base\Helpers\Creatuity\Subjects\Seo;
use Creatuity\Base\Helpers\Creatuity\Subjects\Setting;
use Creatuity\Base\Helpers\Creatuity\Subjects\Store;
use Creatuity\Base\Helpers\Creatuity\Subjects\SubjectForModuleInterface;
use Creatuity\Base\Helpers\Creatuity\Subjects\Theme;
use Creatuity\Base\Helpers\Creatuity\SubjectsFactory;
use Creatuity\Base\Helpers\Creatuity\SubjectsFactoryFactory;
use Creatuity\Base\Helpers\Creatuity\Subjects\Report;
use Creatuity\Base\Helpers\Creatuity\Subjects\Resources;
use Creatuity\Base\Helpers\Creatuity\Subjects\SubjectAbstract;
use Creatuity\Base\Model\CsvParser\CsvParserInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\Config\MutableScopeConfigInterface;
use Magento\Framework\App\State;
use Magento\Framework\Registry;
use Magento\Indexer\Model\Processor;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package m2newbuild
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
class Creatuity extends BackwardCompatibilityHelper
{
    /**
     * @var SubjectsFactory
     */
    protected $subjectsFactory;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var string
     */
    protected $scope;

    public function __construct(
        SubjectsFactoryFactory $subjectsFactory,
        ResourceConnection $connectionsPool, MutableScopeConfigInterface $scopeConfig, State $magentoArea, Registry $registry, Processor $indexer,
        OutputInterface $defaultOutput,
        $scope = '',
        OutputInterface $output = null
    ) {
        parent::__construct($connectionsPool, $scopeConfig, $magentoArea, $registry, $indexer);

        $this->subjectsFactory = $subjectsFactory->create();
        $this->output = $output ? $output : $defaultOutput;
        $this->scope = $scope;
    }

    /**
     * @param string $forModule
     *
     * @return Catalog
     */
    public function catalog($forModule = '')
    {
        return $this->obtainModuleSubject('catalog', $forModule);
    }

    /**
     * @param string $forModule
     *
     * @return Cms
     */
    public function cms($forModule = '')
    {
        return $this->obtainModuleSubject('cms', $forModule);
    }

    /**
     * @return Logo
     */
    public function creatuityLogo()
    {
        return $this->obtainSubject('logo');
    }

    /**
     * @param string $forModule
     *
     * @return CsvParserInterface
     */
    public function csv($forModule = '')
    {
        return $this->obtainModuleSubject('csv', $forModule);
    }

    /**
     * @return Database
     */
    public function database()
    {
        return $this->obtainSubject('database');
    }

    /**
     * @return CreatuityDemo
     */
    public function demo()
    {
        return $this->obtainSubject('creatuityDemo');
    }

    /**
     * @return Emulate
     */
    public function emulate()
    {
        return $this->obtainSubject('emulate');
    }

    /**
     * @return Indexer
     */
    public function indexer()
    {
        return $this->obtainSubject('indexer');
    }

    /**
     * @return Report
     */
    public function report()
    {
        if ( $this->output ) {
            return $this->obtainSubject('report', false, ['output' => $this->output]);
        }
        return $this->obtainSubject('Report\ReportNull', false, ['output' => null]);
    }

    /**
     * @param string $forModule
     *
     * @return Resources
     */
    public function resources($forModule = '')
    {
        return $this->obtainModuleSubject('resources', $forModule);
    }

    /**
     * @return Seo
     */
    public function seo()
    {
        return $this->obtainSubject('seo');
    }

    /**
     * @param int $scope
     * @param string $scopeType
     *
     * @return Setting
     */
    public function setting($scope = 0, $scopeType = 'default')
    {
        return $this->obtainSubject('setting', true, ['scopeType' => $scopeType, 'scope' => $scope]);
    }

    /**
     * @return Store
     */
    public function store()
    {
        return $this->obtainSubject('store');
    }

    /**
     * @return Theme
     */
    public function theme()
    {
        return $this->obtainSubject('theme');
    }

    /**
     * @return Processing
     */
    public function processing()
    {
        return $this->obtainSubject('processing');
    }

    /**
     * @param string $subjectName
     * @param string $forModule
     *
     * @return SubjectAbstract
     */
    protected function obtainModuleSubject($subjectName, $forModule = '')
    {
        if ( $forModule ) {
            /** @var SubjectForModuleInterface $subject */
            $subject = $this->obtainSubject($subjectName, true);

            return $subject->forModule($forModule);
        } else {
            return $this->obtainSubject($subjectName);
        }
    }

    /**
     * @param string $subjectName
     * @param bool $isNew
     *
     * @return SubjectAbstract
     */
    protected function obtainSubject($subjectName, $isNew = false, array $arguments = [])
    {
        return $this->subjectsFactory->obtain($subjectName, $this, $this->scope, $isNew, $arguments);
    }
}