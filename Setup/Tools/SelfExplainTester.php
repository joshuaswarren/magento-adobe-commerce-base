<?php

namespace Creatuity\Base\Setup\Tools;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2018 Joshua Warren (https://warrenappliedlabs.com)
 */
class SelfExplainTester
{
    /**
     * @var int
     */
    protected $warningMessageCodeLinesMinCount;

    /**
     * @param int $warningMessageCodeLinesMinCount
     */
    public function __construct($warningMessageCodeLinesMinCount = 17)
    {
        $this->warningMessageCodeLinesMinCount = $warningMessageCodeLinesMinCount;
    }


    /**
     * @param object $instance
     * @param string $method
     * @param \Exception $nestedException
     * @return string
     */
    public function ensureIsSelfExplaining($instance, $method, \Exception $nestedException = null)
    {
        $codeLines = $this->determineFunctionCodeLines($instance, $method);
        if (count($codeLines) > $this->warningMessageCodeLinesMinCount) {
            $msg = "Please explain us the purpose of the upgrade script by calling \$this->creatuity()->report()->printMessage() at the beginning of %s::%s()\n" .
                "In example: \$this->report()->printMessage(\"Adding new cart rule...\");";
            throw new \Exception(sprintf($msg, get_class($instance), $method), 0, $nestedException);
        }
        return implode('', $codeLines);
    }

    /**
     * @param object $instance
     * @param string $method
     * @return array
     */
    protected function determineFunctionCodeLines($instance, $method)
    {
        $reflectionMethod = new \ReflectionMethod(get_class($instance), $method);

        $filename = $reflectionMethod->getFileName();
        $startLine = $reflectionMethod->getStartLine() - 1;
        $endLine = $reflectionMethod->getEndLine();
        $numOfLines = $endLine - $startLine;

        $sourceCodeLines = file($filename);
        $methodBodyInLines = array_slice($sourceCodeLines, $startLine, $numOfLines);
        return $methodBodyInLines;
    }
}