<?php

namespace kristijorgji\DbToPhp\Rules\Php;

class PhpObjectType extends PhpType
{
    /**
     * @var string
     */
    private $className;

    /**
     * @param bool $nullable
     * @param string $className
     */
    public function __construct(bool $nullable, string $className)
    {
        parent::__construct(
            new PhpTypes(new PhpTypes(PhpTypes::OBJECT)),
            $nullable
        );
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }
}
