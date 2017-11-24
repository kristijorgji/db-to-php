<?php

namespace kristijorgji\DbToPhp\Support;

class TextBuffer
{
    private $output;

    /**
     * @param string $output
     */
    public function __construct(string $output = '')
    {
        $this->output = $output;
    }

    /**
     * @return string
     */
    public function get() : string
    {
        return $this->output;
    }

    /**
     * @param string $text
     * @param int $indentationSpaces
     */
    public function add(string $text,  int $indentationSpaces = 0)
    {
        if ($indentationSpaces > 0) {
            $this->output .= str_repeat(' ', $indentationSpaces);
        }

        $this->output .= $text;
    }

    /**
     * @param string $text
     * @param int $indentationSpaces
     */
    public function addLine(string $text, int $indentationSpaces = 0)
    {
        if ($indentationSpaces > 0) {
            $this->output .= str_repeat(' ', $indentationSpaces);
        }

        $this->output .= $text . PHP_EOL;
    }

    /**
     * @param int $nr
     */
    public function addEmptyLines(int $nr = 1)
    {
        $this->output .= str_repeat(PHP_EOL, $nr);
    }
}
