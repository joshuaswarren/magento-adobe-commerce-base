<?php

namespace Magento\Framework\Setup\Patch;

// Fake interface, to imitate one from 2.3

/**
 * This interface describe script, that atomic operations with data (DML, DQL) in SQL database
 * This is wrapper for @see PatchInterface in order to define what kind of patch we have
 */
interface DataPatchInterface extends \Creatuity\Base\Setup\Abstracts\Patch\PatchInterface
{
}
