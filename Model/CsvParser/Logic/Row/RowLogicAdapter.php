<?php

namespace Creatuity\Base\Model\CsvParser\Logic\Row;

use Creatuity\Base\Model\CsvParser\ChunkLogicInterface;
use Creatuity\Base\Model\CsvParser\LogicInterface;
use Creatuity\Base\Model\CsvParser\Parser;
use Creatuity\Base\Model\CsvParser\UtilityInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class RowLogicAdapter implements ChunkLogicInterface
{
    /** @var LogicInterface */
    protected $rowLogic;

    /** @var Parser */
    protected $parser;

    public function __construct(LogicInterface $rowLogic, Parser $parser)
    {
        $this->rowLogic = $rowLogic;
        $this->parser = $parser;
    }


    public function beforeProcess(UtilityInterface $utility)
    {
        $this->rowLogic->beforeProcess($utility);
    }

    /**
     * @param array[] $chunkRows
     * @return mixed
     */
    public function processChunk(array $chunkRows, UtilityInterface $utility)
    {
        $isLast = $this->parser->isLast();
        if ( $isLast ) {
            $this->parser->setIsLast(false);
            $rowCount = count($chunkRows);
            $rowCounter = 0;
        }

        foreach ( $chunkRows as $rowData ) {
            if ( !$this->parser->isRunning() ) {
                break;
            }

            if ( $isLast && ++$rowCounter == $rowCount ) {
                $this->parser->setIsLast(true);
            }

            $this->rowLogic->processRow($rowData, $utility);

            $this->parser->setIsFirst(false);
        }
    }

    public function afterProcess(UtilityInterface $utility)
    {
        $this->rowLogic->afterProcess($utility);
    }
}