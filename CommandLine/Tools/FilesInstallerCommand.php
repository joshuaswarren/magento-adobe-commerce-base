<?php

namespace Creatuity\Base\CommandLine\Tools;

use Creatuity\Base\CommandLine\AbstractCommand;
use Creatuity\Base\Setup\Abstracts\Files\UpgradeFilesInstaller;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class FilesInstallerCommand extends AbstractCommand
{
    protected string $name = 'creatuity:tools:install-files';
    protected string $description = 'Installs media files from modules to project root folder. Purpose of this technique is to avoid collisions with 3rd party development teams';

    private UpgradeFilesInstaller $baseSetup;

    public function __construct(UpgradeFilesInstaller $baseSetup)
    {
        parent::__construct();
        $this->baseSetup = $baseSetup;
    }

    protected function runCommand(InputInterface $input, OutputInterface $output): void
    {
        $this->baseSetup->upgradeFiles();
    }
}
