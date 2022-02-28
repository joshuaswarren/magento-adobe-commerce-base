<?php

namespace Creatuity\Base\Setup\Abstracts\Patch;

\Creatuity\Base\Model\MagentoVersion::instance()->includeIfLowerThan(
    '2.3', __DIR__ . '/Earlier23.inc'
);

interface PatchInterface extends \Magento\Framework\Setup\Patch\PatchInterface
{}
