<?php

namespace Magento\Framework\Setup\Patch;

// Fake interface, to imitate one from 2.3

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */

interface PatchInterface
{
    /**
     * Get aliases (previous names) for the patch.
     *
     * @return string[]
     */
    public function getAliases();

    /**
     * Run code inside patch
     * If code fails, patch must be reverted, in case when we are speaking about schema - than under revert
     * means run PatchInterface::revert()
     *
     * If we speak about data, under revert means: $transaction->rollback()
     *
     * @return $this
     */
    public function apply();
}