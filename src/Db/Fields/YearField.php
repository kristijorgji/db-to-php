<?php

namespace kristijorgji\DbToPhp\Db\Fields;

class YearField extends Field
{
    /**
     * @var int
     */
    private $digits;

    /**
     * @param string $name
     * @param bool $nullable
     * @param int $digits
     */
    public function __construct(
        string $name,
        bool $nullable,
        int $digits = 4
    )
    {
        parent::__construct($name, $nullable);
        $this->digits = $digits;
    }

    /**
     * @return int
     */
    public function getDigits(): int
    {
        return $this->digits;
    }
}
