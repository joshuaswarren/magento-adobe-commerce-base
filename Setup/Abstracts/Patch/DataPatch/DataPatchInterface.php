<?php

namespace Creatuity\Base\Setup\Abstracts\Patch\DataPatch;

\Creatuity\Base\Model\MagentoVersion::instance()->includeIfLowerThan(
    '2.3', __DIR__ . '/Earlier23.inc'
);


interface DataPatchInterface extends \Magento\Framework\Setup\Patch\DataPatchInterface
{}
