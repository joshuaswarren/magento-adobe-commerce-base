<?php

namespace Creatuity\Base\Helpers;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndentOutputDecorator implements OutputInterface
{
    public static function decorate(OutputInterface $decorated, $indentChar = ' ', $indentSize = 4)
    {
        return new IndentOutputDecorator($decorated, $indentChar, $indentSize);
    }


    /**
     * @var OutputInterface
     */
    protected $decorated;

    /**
     * @var string
     */
    protected $indent;

    /**
     * @var bool
     */
    protected $isBeginningRow = true;

    public function __construct(OutputInterface $decorated, $indentChar = ' ', $indentSize = 4)
    {
        $this->decorated = $decorated;
        $this->indent = str_repeat($indentChar, $indentSize);
    }

    public function isDecorated(): bool
    {
        return true;
    }

    protected function decorateMessage($message, $indentEmptyLines)
    {
        if (!$this->isBeginningRow) {
            return $message;
        }

        $indentMessage = '';
        $separator = '';
        foreach(explode("\n", $message) as $line) {
            if (empty($line)) {
                $indentMessage .= $separator;
                if ($indentEmptyLines) {
                    $indentMessage .= $this->indent;
                }
                continue;
            }
            $indentMessage .= $separator . $this->indent . $line;
            $separator = "\n";
        }
        if ($indentMessage === '' && $indentEmptyLines) {
            return $this->indent . $indentMessage;
        }
        return $indentMessage;
    }

    public function write($messages, $newline = false, $options = 0)
    {
        if (!$this->hasAny($messages)) {
            return $this->decorated->write($messages, $newline, $options);
        }

        foreach($this->allMessages($messages) as $message) {
            $this->decorated->write($this->decorateMessage($message, false), $newline, $options);
            $this->isBeginningRow = $this->isLastCharIsEndOfLine($message);
        }
    }

    public function writeln($messages, $options = 0)
    {
        if (!$this->hasAny($messages)) {
            return $this->decorated->writeln($messages, $options);
        }

        foreach($this->allMessages($messages) as $message) {
            $this->decorated->writeln($this->decorateMessage($message, true), $options);
            $this->isBeginningRow = true;
        }
    }

    protected function isLastCharIsEndOfLine($message)
    {
        return $message[strlen($message) - 1] == "\n" ||
            $message[strlen($message) - 1] == "\r" && $message[strlen($message) - 2] == "\n";
    }

    /**
     * @return array
     */
    private function allMessages($messages)
    {
        if ($messages instanceof \Traversable) {
            return iterator_to_array($messages);
        }
        return (array)$messages;
    }

    /**
     * @return bool
     */
    protected function hasAny($messages)
    {
        foreach((array)$messages as $message) {
            return true;
        }
        return false;
    }

    public function setVerbosity($level)
    {
        return $this->decorated->setVerbosity($level);
    }

    public function getVerbosity(): int
    {
        return $this->decorated->getVerbosity();
    }

    public function isQuiet(): bool
    {
        return $this->decorated->isQuiet();
    }

    public function isVerbose(): bool
    {
        return $this->decorated->isVerbose();
    }

    public function isVeryVerbose(): bool
    {
        return $this->decorated->isVeryVerbose();
    }

    public function isDebug(): bool
    {
        return $this->decorated->isDebug();
    }

    public function setDecorated(bool $decorated)
    {
        return $this->decorated->setDecorated($decorated);
    }

    public function setFormatter(OutputFormatterInterface $formatter)
    {
        return $this->decorated->setFormatter($formatter);
    }

    public function getFormatter(): OutputFormatterInterface
    {
        return $this->decorated->getFormatter();
    }
}