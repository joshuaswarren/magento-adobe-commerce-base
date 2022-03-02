<?php

namespace Creatuity\Base\Setup\Abstracts;

use Creatuity\Base\Helpers\Creatuity\Subjects\Report\ReportObserverInterface;
use Creatuity\Base\Setup\Abstracts\ModuleContext\ModuleContextInterface;
use Creatuity\Base\Setup\Abstracts\ModuleContext\ModuleContextInterfaceImplFactory;
use Creatuity\Base\Setup\AbstractUpgradeData;
use Creatuity\Base\Setup\Tools\SelfExplainTester;
use Magento\Framework\Setup\ModuleContextInterface as CoreModuleContextInterface;
use ReflectionClass;

/**
 * @package base2
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
abstract class AbstractUpgradeSchemaDataImpl extends AbstractInstallUpgradeSchemaData implements ReportObserverInterface
{
    /**
     * @var ModuleContextInterfaceImplFactory
     */
    protected $contextFactory;

    /**
     * @var bool
     */
    protected $disableForeignKeysAndAllowZeroIds = true;

    /**
     * @var bool
     */
    private $somethingWasPrintedToTheReport = false;

    /**
     * @var SelfExplainTester
     */
    private $selfExplainTester;

    public function __construct(AbstractUpgradeDataContext $context)
    {
        parent::__construct($context);

        $this->contextFactory = $context->getContextFactory();
        $this->selfExplainTester = $context->getSelfExplainTester();
    }

    protected function run($setup, CoreModuleContextInterface $context)
    {
        try {
            if ($this->disableForeignKeysAndAllowZeroIds) {
                $setup->startSetup();
            }

            $ourContext = $this->contextFactory->create([
                'orgContext' => $context,
                'setupClassName' => get_class($this),
            ]);

            $this->creatuity()->report()->registerObserver($this);

            $this->doUpgrade($setup, $ourContext);
        } finally {
            $this->creatuity()->report()->unregisterObserver($this);
            if ($this->disableForeignKeysAndAllowZeroIds) {
                $setup->endSetup();
            }
        }
    }

    protected function doUpgrade($setup, ModuleContextInterface $context)
    {
        $this->creatuity()->report()->ensureNextOutputWillBeSeparated();
        $this->callVersionsAutomatically($setup, $context);
    }

    private function callVersionsAutomatically($setup, ModuleContextInterface $context)
    {
        foreach ($this->collectMethodsSortedByVersion() as $version => $method) {
            if ($this->isVersionNeedsInstallation($context, $version)) {
                $this->callUpgrade($version, $method, func_get_args());
            }
        }
    }

    private function collectMethodsSortedByVersion()
    {
        $ret = [];

        foreach ((new ReflectionClass($this))->getMethods() as $method) {
            $matches = [];
            if (!preg_match('/^upgrade_((\d+)_(\d+)_(\d+$))/', $method->getName(), $matches)) {
                // it's not an upgrade method
                continue;
            }

            if (AbstractUpgradeData::class == $method->getDeclaringClass()->getName()
                && 'upgrade_1_0_0' == $method->getName()
            ) {
                // skip example method
                continue;
            }

            $version = str_replace('_', '.', $matches[1]);

            $ret[ $version ] = [$this, $method->getName()];
        }

        uksort($ret, 'version_compare');

        return $ret;
    }

    /**
     * @return bool
     */
    protected function isVersionNeedsInstallation(ModuleContextInterface $context, $version)
    {
        if ($context->isAlreadyInstalledInDb()) {
            return $context->isVersionInDbIsLowerThan($version);
        }

        return $context->isVersionInFilesIsHigherThan($version)
            || $context->isVersionInFilesIs($version);
    }

    protected function callUpgrade($version, $callback, array $args)
    {
        try {
            $this->handleScriptPreparation($callback);

            $this->somethingWasPrintedToTheReport = false;
            $ret = call_user_func_array($callback, $args);

            if (!$this->somethingWasPrintedToTheReport) {
                $this->selfExplainTester->ensureIsSelfExplaining($this, $callback[1]);
            }

            $this->handleScriptSuccess($callback);

            return $ret;
        } catch (\Exception $e) {
            if (!$this->somethingWasPrintedToTheReport) {
                $this->selfExplainTester->ensureIsSelfExplaining($this, $callback[1]);
            }
            $this->handleScriptFailure($callback, $e);
            throw $e;
        }
    }

    public function handleReportEvent(string $name, array $args): void
    {
        $this->somethingWasPrintedToTheReport = true;
    }

    protected function handleScriptPreparation($callback)
    {
        $this->creatuity()->report()->printEmptySeparator(1);
        $this->creatuity()->report()->printLine('=');
        $this->creatuity()->report()->printMessage(sprintf("%s - Start", $this->callbackName($callback)));
        $this->creatuity()->report()->printLine('-');
    }

    protected function handleScriptSuccess($callback)
    {
        $this->creatuity()->report()->printLine('-');
        $this->creatuity()->report()->printSuccess(sprintf("%s - Succeeded.", $this->callbackName($callback)));
        $this->creatuity()->report()->printLine('=');
        $this->creatuity()->report()->printEmptySeparator(2);
    }

    protected function handleScriptFailure($callback, \Exception $e)
    {
        $this->creatuity()->report()->printLine('-');
        $this->creatuity()->report()->printError(sprintf("%s - Failed:\n%s", $this->callbackName($callback), $e->getMessage()), $e);
        $this->creatuity()->report()->printLine('-');
        $this->creatuity()->report()->printError(sprintf("%s\n", (string)$e));
        $this->creatuity()->report()->printLine('=');
        $this->creatuity()->report()->printEmptySeparator(2);
    }

    /**
     * @return string
     */
    private function callbackName(callable $callback)
    {
        $class = isset($callback[0]) ? get_class($callback[0]) : '';
        $method = isset($callback[1]) ? $callback[1] : '';

        if ($class) {
            $classArr = explode("\\", $class);
            $class = implode("\\", [ $classArr[1], $classArr[3]]);
        }

        if ($class && $method) {
            $name = $class . '::' . $method;
        } else {
            $name = $class;
        }

        return $name;
    }
}
