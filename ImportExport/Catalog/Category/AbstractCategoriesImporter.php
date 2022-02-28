<?php
namespace Creatuity\Base\ImportExport\Catalog\Category;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Model\Catalog\CategoriesModifier;
use Magento\Framework\Filesystem\File\ReadFactory;
use Symfony\Component\Console\Output\OutputInterface;
use Creatuity\Base\Model\Catalog\CategoriesModifierFactory;


/**
 * @package waltwo
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
abstract class AbstractCategoriesImporter implements CategoriesImporterInterface
{

    /**
     * @var CategoriesModifierFactory
     */
    protected $categoriesModifierFactory;

    /**
     * @var CategoriesModifier
     */
    protected $categoriesModifier;

    /**
     * @var ReadFactory
     */
    protected $readFactory;

    /**
     * @var Creatuity
     */
    protected $helper;

    public function __construct(CategoriesModifierFactory $categoriesModifierFactory, ReadFactory $readFactory, Creatuity $helper)
    {
        $this->categoriesModifierFactory = $categoriesModifierFactory;
        $this->readFactory = $readFactory;
        $this->helper = $helper;
    }

    public function import($absFile, OutputInterface $output = null, array $config = [])
    {
        $data = $this->readAndTransformData($absFile);

        $this->categoriesModifier = $this->categoriesModifierFactory->create();
        $this->categoriesModifier->process($data, $config + [
            'output' => $output
        ]);
    }

    /**
     * @return array
     */
    abstract protected function readAndTransformData($absFile);


}