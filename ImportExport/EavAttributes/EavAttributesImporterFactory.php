<?php


namespace Creatuity\Base\ImportExport\EavAttributes;

use Magento\Framework\ObjectManagerInterface;

class EavAttributesImporterFactory
{
    protected $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create($entityTypeId)
    {
        return $this->objectManager->create(EavAttributesImporter::class, ['entityTypeId' => $entityTypeId]);
    }
}