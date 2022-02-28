<?php
namespace Creatuity\Base\Model\Catalog\CategoriesModifier;


use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\IndentOutputDecorator;
use Magento\Store\Model\Store;
use Symfony\Component\Console\Output\OutputInterface;

class Helper
{

    /**
     * @var Creatuity
     */
    protected $creatuity;
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(
        Creatuity $creatuity,
        Config $config
    ) {
        $this->creatuity = $creatuity;
        $this->config = $config;
    }

    public function runIndented($callback, array $args = [])
    {
        try{
            $toRestore = $this->output;

            $this->output = IndentOutputDecorator::decorate($this->output());

            return \call_user_func_array($callback, $args);
        } finally {
            $this->output = $toRestore;
        }
    }

    public function log($msg)
    {
        $this->output()->writeln($msg);
    }

    public function throwError(array $item, $msg)
    {
        throw new CategoriesModifierException("{$msg}\nfor category item:\n" . var_export($item, true));
    }

    public function runSafely($callback, array $args = [])
    {
        return $this->creatuity->emulate()->runInStore(Store::DEFAULT_STORE_ID, function() use ($callback, $args) {
            return $this->creatuity->emulate()->runInSecuredArea(function() use ($callback, $args) {
                return $this->creatuity->database()->runInTransaction(function() use ($callback, $args) {
                    return call_user_func_array($callback, $args);
                });
            });
        });
    }

    protected function output()
    {
        if ($this->output) {
            $output = $this->output;
        } else {
            $output = $this->config->output();
        }

        if (!$output) {
            throw new CategoriesModifierException("no output");
        }
        return $output;
    }


}