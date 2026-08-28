<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Rules\Php;

class PhpFunctionParameter
{
    public function __construct(private string $name, private PhpType $type)
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
