<?php

namespace kristijorgji\DbToPhp\Db\Fields;

class DecimalField extends Field
{
    /**
     * @var int
     */
    private $decimalPrecision;

    /**
     * @var int
     */
    private $fractionalPrecision;

    /**
     * @var bool
     */
    private $signed;

    /**
     * @param string $name
     * @param string $type
     * @param bool $nullable
     * @param int $decimalPrecision
     * @param int $fractionalPrecision
     * @param bool $signed
     */
    public function __construct(
        string $name,
        string $type,
        bool $nullable,
        int $decimalPrecision,
        int $fractionalPrecision = 0,
        bool $signed = false
    )
    {
        parent::__construct($name, $type, $nullable);
        $this->decimalPrecision = $decimalPrecision;
        $this->fractionalPrecision = $fractionalPrecision;
        $this->signed = $signed;
    }

    /**
     * @return int
     */
    public function getDecimalPrecision(): int
    {
        return $this->decimalPrecision;
    }

    /**
     * @return int
     */
    public function getFractionalPrecision(): int
    {
        return $this->fractionalPrecision;
    }

    /**
     * @return boolean
     */
    public function isSigned(): bool
    {
        return $this->signed;
    }
}
