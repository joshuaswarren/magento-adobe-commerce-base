<?php

namespace Creatuity\Base\Model\CsvParser\Logic\Row;

use Creatuity\Base\Model\CsvParser\ChunkLogicInterface;
use Creatuity\Base\Model\CsvParser\LogicInterface;
use Creatuity\Base\Model\CsvParser\Parser;
use Creatuity\Base\Model\CsvParser\UtilityInterface;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class RowLogicAdapter implements ChunkLogicInterface
{
    private LogicInterface $rowLogic;
    private Parser $parser;

    public function __construct(
        LogicInterface $rowLogic,
        Parser $parser
    ) {
        $this->rowLogic = $rowLogic;
        $this->parser = $parser;
    }

    public function beforeProcess(UtilityInterface $utility): void
    {
        $this->rowLogic->beforeProcess($utility);
    }

    public function processChunk(array $chunkRows, UtilityInterface $utility): void
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

    public function afterProcess(UtilityInterface $utility): void
    {
        $this->rowLogic->afterProcess($utility);
    }
}
