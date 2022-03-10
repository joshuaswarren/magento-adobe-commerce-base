<?php

namespace Creatuity\Base\Setup\Abstracts;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Subjects\Report\ReportObserverInterface;
use Creatuity\Base\Setup\Abstracts\Patch\PatchInterface;
use Creatuity\Base\Setup\Tools\SelfExplainTester;
use Exception;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 *
 * @deprecated Use core DataPatchInterface, include desired pieces via constructor
 */
abstract class AbstractPatch implements PatchInterface, ReportObserverInterface
{
    private Creatuity $creatuity;
    private SelfExplainTester $selfExplainTester;
    private bool $isSomethingPrinted;

    public function __construct(AbstractPatchContext $patchContext)
    {
        $this->creatuity = $patchContext->getCreatuity()->forModule($patchContext->getModuleNameResolver()->byObject($this));
        $this->selfExplainTester = $patchContext->getSelfExplainTester();
    }

    /**
     * @throws Exception
     */
    final public function apply(): AbstractPatch
    {
        try {
            $this->creatuity()->report()->registerObserver($this);

            $this->handlePatchPreparation();
            $this->isSomethingPrinted = false;

            $this->applyPatch();

            if (!$this->isSomethingPrinted) {
                $this->selfExplainTester->ensureIsSelfExplaining($this, 'applyPatch');
            }

            $this->handlePatchSuccess();
        }  catch ( Exception $e ) {
            $this->handlePatchFailure($e);
            throw $e;
        } finally {
            $this->creatuity()->report()->unregisterObserver($this);

            if (!$this->isSomethingPrinted) {
                $this->selfExplainTester->ensureIsSelfExplaining($this, 'applyPatch');
            }
        }

        return $this;
    }

    abstract protected function applyPatch(): AbstractPatch;

    private function handlePatchPreparation(): void
    {
        $this->creatuity()->report()->printEmptySeparator(1);
        $this->creatuity()->report()->printLine('=');
        $this->creatuity()->report()->printMessage(sprintf("%s - Start", get_class($this)));
        $this->creatuity()->report()->printLine('-');
    }

    private function handlePatchSuccess(): void
    {
        $this->creatuity()->report()->printLine('-');
        $this->creatuity()->report()->printSuccess(sprintf("%s - Succeeded.", get_class($this)));
        $this->creatuity()->report()->printLine('=');
        $this->creatuity()->report()->printEmptySeparator(2);
    }

    private function handlePatchFailure(Exception $e): void
    {
        $this->creatuity()->report()->printLine('-');
        $this->creatuity()->report()->printError(sprintf("%s - Failed:\n%s", get_class($this), $e->getMessage()), $e);
        $this->creatuity()->report()->printLine('-');
        $this->creatuity()->report()->printError(sprintf("%s\n", (string)$e));
        $this->creatuity()->report()->printLine('=');
        $this->creatuity()->report()->printEmptySeparator(2);
    }

    public function handleReportEvent(string $name, array $args): void
    {
        $this->isSomethingPrinted = true;
    }

    public function creatuity(): Creatuity
    {
        return $this->creatuity;
    }

    public function getAliases(): array
    {
        return [];
    }
}
