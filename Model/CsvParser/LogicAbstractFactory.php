<?php

namespace Creatuity\Base\Model\CsvParser;

use Closure;
use Exception;
use Magento\Framework\ObjectManagerInterface;

abstract class LogicAbstractFactory
{
    private ObjectManagerInterface $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param Closure|string $type
     *
     * @return ChunkLogicInterface
     * @throws ParserException
     */
    public function create($type = null): ChunkLogicInterface
    {
        $logic = null;
        if (is_null($type)) {
            $type = $this->emptyLogicClassName();
        }

        if (is_object($type) && !$type instanceof Closure) {
            $logic = $type;
        } else {
            try {
                $logic = is_callable($type)
                    ? $this->objectManager->create($this->closureLogicClassName(), ['closure' => $type])
                    : $this->objectManager->create($type);
            } catch (Exception $e) {
                throw new ParserException('No csv logic model found', $e->getCode(), $e);
            }
        }

        $logicInterfaceName = $this->logicInterfaceName();
        if (!$logic instanceof $logicInterfaceName ) {
            throw new ParserException('Logic model should implements ' . $logicInterfaceName);
        }

        return $logic;
    }

    abstract protected function emptyLogicClassName(): string;

    abstract protected function closureLogicClassName(): string;

    abstract protected function logicInterfaceName(): string;
}
