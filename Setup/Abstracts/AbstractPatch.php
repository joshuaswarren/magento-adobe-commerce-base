<?php

namespace Creatuity\Base\Setup\Abstracts;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Subjects\CreatuityDemo;
use Creatuity\Base\Helpers\Creatuity\Subjects\Report\ReportObserverInterface;
use Creatuity\Base\Setup\Abstracts\Patch\PatchInterface;
use Creatuity\Base\Setup\Tools\SelfExplainTester;
use Creatuity\Base\Setup\Type\Catalog;
use Creatuity\Base\Setup\TypeFactory;
use Creatuity\Base\Setup\TypeInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
abstract class AbstractPatch implements PatchInterface, ReportObserverInterface, TypeInterface
{
    /**
     * @var Creatuity
     */
    private $creatuity;

    /**
     * @var SelfExplainTester
     */
    private $selfExplainTester;

    /**
     * @var bool
     */
    private $isSomethingPrinted;

    /**
     * @var TypeFactory
     */
    private $typeFactory;

    public function __construct(AbstractPatchContext $patchContext)
    {
        $this->creatuity = $patchContext->getCreatuity()->forModule($patchContext->getModuleNameResolver()->byObject($this));
        $this->selfExplainTester = $patchContext->getSelfExplainTester();
        $this->typeFactory = $patchContext->getTypeFactory();
    }

    /**
    * @return $this
    */
    final public function apply()
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
        }  catch ( \Exception $e ) {
            $this->handlePatchFailure($e);
            throw $e;
        } finally {
            $this->creatuity()->report()->unregisterObserver($this);

            if (!$this->isSomethingPrinted) {
                $this->selfExplainTester->ensureIsSelfExplaining($this, 'applyPatch');
            }
        }
    }

    /**
     * @return $this
     */
    abstract protected function applyPatch();

    private function handlePatchPreparation()
    {
        $this->creatuity()->report()->printEmptySeparator(1);
        $this->creatuity()->report()->printLine('=');
        $this->creatuity()->report()->printMessage(sprintf("%s - Start", get_class($this)));
        $this->creatuity()->report()->printLine('-');
    }

    private function handlePatchSuccess()
    {
        $this->creatuity()->report()->printLine('-');
        $this->creatuity()->report()->printSuccess(sprintf("%s - Succeeded.", get_class($this)));
        $this->creatuity()->report()->printLine('=');
        $this->creatuity()->report()->printEmptySeparator(2);
    }

    private function handlePatchFailure(\Exception $e)
    {
        $this->creatuity()->report()->printLine('-');
        $this->creatuity()->report()->printError(sprintf("%s - Failed:\n%s", get_class($this), $e->getMessage()), $e);
        $this->creatuity()->report()->printLine('-');
        $this->creatuity()->report()->printError(sprintf("%s\n", (string)$e));
        $this->creatuity()->report()->printLine('=');
        $this->creatuity()->report()->printEmptySeparator(2);
    }

    public function handleReportEvent($name, array $args)
    {
        $this->isSomethingPrinted = true;
    }

    /**
     * @return CreatuityDemo
     */
    public function creatuityDemo()
    {
        return $this->creatuity()->demo();
    }

    /**
     * @return Creatuity
     */
    public function creatuity()
    {
        return $this->creatuity;
    }

    /**
     * @return Catalog
     */
    protected function eavCatalog()
    {
        return $this->typeFactory->create('catalog', $this);
    }
    /**
     * @return Customer
     */
    protected function eavCustomer()
    {
        return $this->typeFactory->create('customer', $this);
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return [];
    }
}