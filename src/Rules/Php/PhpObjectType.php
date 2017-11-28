<?php

namespace kristijorgji\DbToPhp\Rules\Php;

class PhpObjectType extends PhpType
{
    private $className;

    /**
     * @param PhpTypes $type
     * @param bool $nullable
     * @param string $className
     */
    public function __construct(PhpTypes $type, bool $nullable, string $className)
    {
        parent::__construct($type, $nullable);
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
