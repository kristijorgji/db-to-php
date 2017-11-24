<?php

namespace kristijorgji\DbToPhp\Rules\Php;

use kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers;
use kristijorgji\DbToPhp\Rules\Php\PhpType;

class PhpProperty
{
    /**
     * @var PhpAccessModifiers
     */
    private $accessModifier;

    /**
     * @var PhpType
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @param PhpAccessModifiers $accessModifier
     * @param PhpType $type
     * @param string $name
     */
    public function __construct(PhpAccessModifiers $accessModifier, PhpType $type, string $name)
    {
        $this->accessModifier = $accessModifier;
        $this->type = $type;
        $this->name = $name;
    }

    /**
     * @return PhpAccessModifiers
     */
    public function getAccessModifier(): PhpAccessModifiers
    {
        return $this->accessModifier;
    }

    /**
     * @return PhpType
     */
    public function getType(): PhpType
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
