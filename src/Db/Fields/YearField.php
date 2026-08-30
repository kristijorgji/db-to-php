<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db\Fields;

class YearField extends Field
{
    public function __construct(
        string $name,
        bool $nullable,
        private readonly int $digits = 4,
    ) {
        parent::__construct($name, $nullable);
    }

    public function getDigits(): int
    {
        return $this->digits;
    }
}
