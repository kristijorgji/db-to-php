<?php

namespace kristijorgji\DbToPhp\Rules\Php;

class PhpFunctionParametersCollection
{
    /**
     * @var PhpFunctionParameter[]
     */
    private $arguments = [];

    /**
     * @param PhpFunctionParameter[] $properties
     */
    public function __construct(PhpFunctionParameter... $properties)
    {
        $this->arguments = $properties;
    }

    /**
     * @return PhpFunctionParameter[]
     */
    public function all() : array
    {
        return $this->arguments;
    }

}
