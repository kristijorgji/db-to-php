<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db\Fields;

class DecimalField extends Field
{
    public function __construct(
        string $name,
        bool $nullable,
        private readonly int $decimalPrecision,
        private readonly int $fractionalPrecision = 0,
        private readonly bool $signed = false,
    ) {
        parent::__construct($name, $nullable);
    }

    public function getDecimalPrecision(): int
    {
        return $this->decimalPrecision;
    }

    public function getFractionalPrecision(): int
    {
        return $this->fractionalPrecision;
    }

    public function isSigned(): bool
    {
        return $this->signed;
    }
}
