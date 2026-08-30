<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Rules\Php;

class PhpObjectType extends PhpType
{
    public function __construct(bool $nullable, private readonly string $className)
    {
        parent::__construct(
            PhpTypes::OBJECT,
            $nullable,
        );
    }

    public function getClassName(): string
    {
        return $this->className;
    }
}
