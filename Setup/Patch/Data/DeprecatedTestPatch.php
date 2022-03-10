<?php

namespace Creatuity\Base\Setup\Patch\Data;

use Creatuity\Base\Setup\AbstractDataPatch;

class DeprecatedTestPatch extends AbstractDataPatch
{
    protected function applyPatch(): self
    {
        $this->creatuity()->report()->printMessage('Creating test deprecated page and block...');

        $this->creatuity()->cms()->blockSave('test-deprecated-block');
        $this->creatuity()->cms()->pageSave('test-deprecated-page');

        return $this;
    }

    public static function getDependencies(): array
    {
        return [];
    }
}

