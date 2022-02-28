<?php

namespace Creatuity\Base\Setup\Abstracts\Patch\SchemaPatch;

\Creatuity\Base\Model\MagentoVersion::instance()->includeIfLowerThan(
    '2.3', __DIR__ . '/Earlier23.inc'
);

interface SchemaPatchInterface extends \Magento\Framework\Setup\Patch\SchemaPatchInterface
{}
