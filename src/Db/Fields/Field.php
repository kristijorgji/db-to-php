<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db\Fields;

abstract class Field
{
    public function __construct(protected string $name, protected bool $nullable)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
