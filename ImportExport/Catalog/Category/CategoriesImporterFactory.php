<?php
namespace Creatuity\Base\ImportExport\Catalog\Category;

use Magento\Framework\ObjectManagerInterface;


/**
 * @package waltwo
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
class CategoriesImporterFactory
{

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    protected $availableModes = [
        'tree_json' => 'Used for simple cases. Usually to create just a stub testing categories for development',
        'list_json' => 'Used when there is too many nested categories. Use full qualified paths to build tree relations',
        'linked_list_json' => 'Same as above, but tree is build out of relations between "this" and "parent" fields',
    ];

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return string[]
     */
    public function listModes()
    {
        return array_keys($this->availableModes);
    }

    /**
     * @return string[]
     */
    public function modesDescriptions()
    {
        return $this->availableModes;
    }

    /**
     * @return string
     */
    public function describeModes()
    {
        $ret = 'Available modes: ' . PHP_EOL;
        foreach ($this->availableModes as $mode => $description) {
            $ret .= " - ${mode} - ${description}" . PHP_EOL;
        }
        return $ret;
    }

    /**
     * @return CategoriesImporterInterface
     */
    public function create($mode)
    {
        $this->validateMode($mode);
        switch ($mode) {
            case 'tree_json':
                return $this->objectManager->create(TreeJsonCategoriesImporter::class);
            case 'list_json':
                return $this->objectManager->create(ListJsonCategoriesImporter::class);
            case 'linked_list_json':
                return $this->objectManager->create(LinkedListJsonCategoriesImporter::class);
        }
    }

    protected function validateMode($mode)
    {
        if (!isset($this->availableModes[$mode])) {
            throw new \Exception("Invalid mode. " . $this->describeModes());
        }
        return $mode;
    }


}