<?php

namespace Creatuity\Base\Model\CsvParser;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Output implements OutputInterface
{
    private \Symfony\Component\Console\Output\OutputInterface $output;
    protected static bool $printedAtLeastOnce = false;

    function __construct(\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->output = $output;
    }

    public function writeln(string $text): void
    {
        if (!self::$printedAtLeastOnce) {
            self::$printedAtLeastOnce = true;
            $this->output->writeln('');
        }
        $this->output->writeln($text);
    }
}
