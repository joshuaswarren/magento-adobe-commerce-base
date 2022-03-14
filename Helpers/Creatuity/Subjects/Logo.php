<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Creatuity\Base\Helpers\Creatuity as CreatuityHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Logo extends SubjectAbstract
{
    private OutputInterface $output;

    public function __construct(OutputInterface $output, CreatuityHelper $creatuity)
    {
        parent::__construct($creatuity);
        $this->output = $output;
    }

    public function writeCreatuityLogo()
    {
        $this->output->write('<fg=red;options=bold>_\\');
        $this->output->write('</><fg=yellow;options=bold>.</><fg=red;options=bold>/_</> <fg=blue;options=bold>CREATUITY</>');
    }
}
