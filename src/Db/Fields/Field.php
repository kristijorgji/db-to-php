<?php

namespace kristijorgji\DbToPhp\Db\Fields;

abstract class Field
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $nullable;

    /**
     * @param string $name
     * @param bool $nullable
     */
    public function __construct(string $name, bool $nullable)
    {
        $this->name = $name;
        $this->nullable = $nullable;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
