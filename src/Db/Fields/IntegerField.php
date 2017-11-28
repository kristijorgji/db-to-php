<?php

namespace kristijorgji\DbToPhp\Db\Fields;

class IntegerField extends Field
{
    /**
     * @var int
     */
    private $lengthInBits;

    /**
     * @var bool
     */
    private $signed;

    /**
     * @param string $name
     * @param string $type
     * @param bool $nullable
     * @param int $lengthInBits
     * @param bool $signed
     */
    public function __construct(
        string $name,
        string $type,
        bool $nullable,
        int $lengthInBits = 32,
        bool $signed = false
    )
    {
        parent::__construct($name, $type, $nullable);
        $this->lengthInBits = $lengthInBits;
        $this->signed = $signed;
    }

    /**
     * @return int
     */
    public function getLengthInBits() : int
    {
        return $this->lengthInBits;
    }

    /**
     * @return bool
     */
    public function isSigned() : bool
    {
        return $this->signed;
    }
}
