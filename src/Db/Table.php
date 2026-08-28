<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db;

class Table
{
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
