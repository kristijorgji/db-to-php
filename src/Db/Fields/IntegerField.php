<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db\Fields;

class IntegerField extends Field
{
    public function __construct(
        string $name,
        bool $nullable,
        private int $lengthInBits = 32,
        private bool $signed = false,
    ) {
        parent::__construct($name, $nullable);
    }

    public function getLengthInBits() : int
    {
        return $this->lengthInBits;
    }

    public function isSigned() : bool
    {
        return $this->signed;
    }
}
