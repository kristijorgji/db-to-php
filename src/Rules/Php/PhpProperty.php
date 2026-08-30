<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Rules\Php;


class PhpProperty
{
    public function __construct(
        private readonly PhpAccessModifiers $accessModifier,
        private readonly PhpType $type,
        private readonly string $name,
    ) {
    }

    public function getAccessModifier(): PhpAccessModifiers
    {
        return $this->accessModifier;
    }

    public function getType(): PhpType
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
