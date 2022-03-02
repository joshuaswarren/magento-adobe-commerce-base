<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class Logo extends SubjectAbstract
{
    public function writeCreatuityLogo(OutputInterface $output)
    {
        $output->write('<fg=red;options=bold>_\\');
        $output->write('</><fg=yellow;options=bold>.</><fg=red;options=bold>/_</> <fg=blue;options=bold>CREATUITY</>');
    }
}
