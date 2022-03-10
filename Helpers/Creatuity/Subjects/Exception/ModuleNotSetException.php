<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects\Exception;

class ModuleNotSetException extends \Exception
{
    protected $message = '
        To properly use this class, ensure to run "forModule" method before any other actions.
        Example: $cmsUtility->forModule(\'Creatuity_Cms\')
    ';
}
