<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Support;

use function str_repeat;
use const PHP_EOL;

class TextBuffer
{
    public function __construct(private string $output = '')
    {
    }

    public function get() : string
    {
        return $this->output;
    }

    public function add(string $text,  int $indentationSpaces = 0): void
    {
        if ($indentationSpaces > 0) {
            $this->output .= str_repeat(' ', $indentationSpaces);
        }

        $this->output .= $text;
    }

    public function addLine(string $text, int $indentationSpaces = 0): void
    {
        if ($indentationSpaces > 0) {
            $this->output .= str_repeat(' ', $indentationSpaces);
        }

        $this->output .= $text . PHP_EOL;
    }

    public function addEmptyLines(int $nr = 1): void
    {
        $this->output .= str_repeat(PHP_EOL, $nr);
    }
}
