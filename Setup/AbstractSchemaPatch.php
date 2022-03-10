<?php

namespace Creatuity\Base\Setup;

use Creatuity\Base\Setup\Abstracts\AbstractPatch;
use Creatuity\Base\Setup\Abstracts\Patch\SchemaPatch\SchemaPatchInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 *
 * @deprecated Use core DataPatchInterface, include desired pieces via constructor
 */
abstract class AbstractSchemaPatch extends AbstractPatch implements SchemaPatchInterface
{
}
