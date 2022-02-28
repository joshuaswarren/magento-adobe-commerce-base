<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class Seo extends SubjectAbstract
{
    /**
     * @param string $humanReadableString
     * @return string
     */
    public function nameToSeoUrlKey($humanReadableString)
    {
        $string = \mb_strtolower($humanReadableString, 'UTF-8');

        //Strip any unwanted characters
        $string = \preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean multiple dashes or whitespaces
        $string = \preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = \preg_replace("/[\s_]/", "-", $string);

        $string = str_replace(' ', '-', $string);

        //Trim dashed
        $string = \trim($string, '-');

        return $string;
    }
}