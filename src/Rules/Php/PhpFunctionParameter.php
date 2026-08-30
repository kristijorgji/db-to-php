<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Rules\Php;

class PhpFunctionParameter
{
    public function __construct(private readonly string $name, private readonly PhpType $type)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): PhpType
    {
        return $this->type;
    }
}
