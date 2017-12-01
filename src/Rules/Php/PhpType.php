<?php

namespace kristijorgji\DbToPhp\Rules\Php;

class PhpType
{
    /**
     * @var PhpTypes
     */
    private $type;

    /**
     * @var bool
     */
    private $nullable;

    /**
     * @param PhpTypes $type
     * @param bool $nullable
     */
    public function __construct(PhpTypes $type, bool $nullable)
    {
        $this->type = $type;
        $this->nullable = $nullable;
    }

    /**
     * @return PhpTypes
     */
    public function getType(): PhpTypes
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
