<?php

namespace kristijorgji\DbToPhp\Db\Fields;

class IntegerField extends Field
{
    /**
     * @var
     */
    private $lengthInBits;

    /**
     * @var
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
     * @return mixed
     */
    public function getLengthInBits()
    {
        return $this->lengthInBits;
    }

    /**
     * @return mixed
     */
    public function isSigned()
    {
        return $this->signed;
    }
}
