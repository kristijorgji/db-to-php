<?php

namespace kristijorgji\DbToPhp\Db;

class Field
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $nullable;

    /**
     * @param string $name
     * @param string $type
     * @param bool $nullable
     */
    public function __construct(string $name, string $type, bool $nullable)
    {
        $this->name = $name;
        $this->type = $type;
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
     * @return string
     */
    public function getType(): string
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
