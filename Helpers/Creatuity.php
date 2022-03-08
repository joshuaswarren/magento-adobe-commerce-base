<?php

namespace Creatuity\Base\Helpers;

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
use Creatuity\Base\Helpers\Creatuity\Subjects\Report;
use Creatuity\Base\Helpers\Creatuity\Subjects\Resources;
use Creatuity\Base\Helpers\Creatuity\Subjects\SubjectAbstract;
use Creatuity\Base\Model\CsvParser\CsvParserInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 *
 * @deprecated This class is now deprecated, you should use Subjects directly
 */
class Creatuity
{
    private string $forceModuleName = '';

    private SubjectsFactory $subjectsFactory;
    private OutputInterface $output;

    public function __construct(
        SubjectsFactory $subjectsFactory,
        OutputInterface $defaultOutput,
        OutputInterface $output = null
    ) {
        $this->subjectsFactory = $subjectsFactory;
        $this->output = $output ? $output : $defaultOutput;
    }

    public function cms(string $forModule = ''): Cms
    {
        return $this->obtainModuleSubject('cms', $forModule);
    }

    public function creatuityLogo(): Logo
    {
        return $this->obtainSubject('logo');
    }

    public function csv(string $forModule = ''): CsvParserInterface
    {
        return $this->obtainModuleSubject('csv', $forModule);
    }

    public function database(): Database
    {
        return $this->obtainSubject('database');
    }

    public function demo(): CreatuityDemo
    {
        return $this->obtainSubject('creatuityDemo');
    }

    public function emulate(): Emulate
    {
        return $this->obtainSubject('emulate');
    }

    public function indexer(): Indexer
    {
        return $this->obtainSubject('indexer');
    }

    public function report(): Report
    {
        if ($this->output) {
            return $this->obtainSubject('report', false, ['output' => $this->output]);
        }

        return $this->obtainSubject('Report\ReportNull', false, ['output' => null]);
    }

    public function resources(string $forModule = ''): Resources
    {
        return $this->obtainModuleSubject('resources', $forModule);
    }

    public function seo(): Seo
    {
        return $this->obtainSubject('seo');
    }

    public function setting(int $scope = 0, string $scopeType = 'default'): Setting
    {
        return $this->obtainSubject('setting', true, ['scopeType' => $scopeType, 'scope' => $scope]);
    }

    public function store(): Store
    {
        return $this->obtainSubject('store');
    }

    public function theme(): Theme
    {
        return $this->obtainSubject('theme');
    }

    public function processing(): Processing
    {
        return $this->obtainSubject('processing');
    }

    public function forModule(string $moduleName): self
    {
        $this->forceModuleName = $moduleName;
        return $this;
    }

    private function obtainModuleSubject(string $subjectName, string $forModule = ''): SubjectAbstract
    {
        if (!empty($this->forceModuleName)) {
            $forModule = $this->forceModuleName;
        }

        if ($forModule) {
            /** @var SubjectForModuleInterface $subject */
            $subject = $this->obtainSubject($subjectName, true);

            return $subject->forModule($forModule);
        } else {
            return $this->obtainSubject($subjectName);
        }
    }

    private function obtainSubject(string $subjectName, bool $isNew = false, array $arguments = []): SubjectAbstract
    {
        return $this->subjectsFactory->obtain($subjectName, $this, $isNew, $arguments);
    }
}
