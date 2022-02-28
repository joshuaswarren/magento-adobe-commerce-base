<?php

namespace Creatuity\Base\Model\CsvParser;

class Output implements OutputInterface
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    protected static $printedAtLeastOnce = false;

    function __construct(\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->output = $output;
    }


    public function writeln($txt)
    {
        if (!self::$printedAtLeastOnce) {
            self::$printedAtLeastOnce = true;
            $this->output->writeln("");
        }
        $this->output->writeln($txt);
    }
}
