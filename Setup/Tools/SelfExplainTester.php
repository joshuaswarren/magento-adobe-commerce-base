<?php

namespace Creatuity\Base\Setup\Tools;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class SelfExplainTester
{
    private int $warningMessageCodeLinesMinCount;

    public function __construct(int $warningMessageCodeLinesMinCount = 17)
    {
        $this->warningMessageCodeLinesMinCount = $warningMessageCodeLinesMinCount;
    }

    public function ensureIsSelfExplaining(object $instance, string $method, \Exception $nestedException = null): string
    {
        $codeLines = $this->determineFunctionCodeLines($instance, $method);

        if (count($codeLines) > $this->warningMessageCodeLinesMinCount) {
            $msg = "Please explain us the purpose of the upgrade script by calling \$this->creatuity()->report()->printMessage() at the beginning of %s::%s()\n" .
                "In example: \$this->report()->printMessage(\"Adding new cart rule...\");";
            throw new \Exception(sprintf($msg, get_class($instance), $method), 0, $nestedException);
        }

        return implode('', $codeLines);
    }

    private function determineFunctionCodeLines(object $instance, string $method): array
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
