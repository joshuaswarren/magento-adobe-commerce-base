<?php

namespace Creatuity\Base\Helpers;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class OurHelperForScripts extends Creatuity
{
    /**
     * @var string
     */
    private $moduleName = '';

    /**
     * @param string $moduleName
     * @return $this
     */
    public function forModule($moduleName)
    {
        $this->moduleName = $moduleName;
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function obtainModuleSubject($subjectName, $forModule = '')
    {
        if ( $forModule && $this->moduleName && $forModule != $this->moduleName ) {
            throw new \Exception(sprintf('Creatuity is dedicated to module "%s". You cannot change module name', $this->moduleName));
        } else {
            $forModule = $this->moduleName;
        }

        return parent::obtainModuleSubject($subjectName, $forModule);
    }
}