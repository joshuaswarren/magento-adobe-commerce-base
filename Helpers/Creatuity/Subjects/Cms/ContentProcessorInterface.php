<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects\Cms;

interface ContentProcessorInterface
{
    public function process(string $content): string;
}
