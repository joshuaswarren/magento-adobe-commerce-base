<?php

namespace Creatuity\Base\Helpers\Creatuity;

use Creatuity\Base\Helpers\Creatuity;
use Creatuity\Base\Helpers\Creatuity\Subjects\SubjectAbstract;
use Magento\Framework\ObjectManagerInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class SubjectsFactory
{
    /**
     * @var SubjectAbstract[]
     */
    protected $instances;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $name
     * @param bool $isNew
     * @param string $scope
     * @return SubjectAbstract
     */
    public function obtain($name, Creatuity $creatuity, $scope = '', $isNew = false, array $arguments = [])
    {
        $className = sprintf('\Creatuity\Base\Helpers\Creatuity\Subjects\\Scopes\\%s\\%s', ucfirst($scope), ucfirst($name));

        if ( !$isNew && !empty($this->instances[$className]) ) {
            return $this->instances[$className];
        }

        if ( !class_exists($className) ) {
            $className = '\Creatuity\Base\Helpers\Creatuity\Subjects\\' . ucfirst($name);
        }

        if ( !$isNew && !empty($this->instances[$className]) ) {
            return $this->instances[$className];
        }

        $this->instances[$className] = $this->objectManager->create($className, ['creatuity' => $creatuity] + $arguments);

        return $this->instances[$className];
    }
}