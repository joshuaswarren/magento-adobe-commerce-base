<?php

namespace Creatuity\Base\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 *
 * @deprecated Do not use in new projects/bash - files are now installed via Data Patches only
 */
class FilesInstallerCommand extends Command
{
    private const MESSAGE = 'Deprecated - left for compatibility with bash_ci_tools package. Will be removed.';

    protected function configure(): void
    {
        $this->setName('creatuity:tools:install-files')
            ->setDescription(self::MESSAGE);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(self::MESSAGE);

        return 0;
    }
}
