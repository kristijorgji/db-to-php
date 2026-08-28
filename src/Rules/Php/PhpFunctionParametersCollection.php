<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Rules\Php;

class PhpFunctionParametersCollection
{
    /**
     * @var array<PhpFunctionParameter>
     */
    private array $arguments = [];

    /**
     * @param PhpFunctionParameter<PhpFunctionParameter> $properties
     */
    public function __construct(PhpFunctionParameter ... $properties)
    {
        $this->arguments = $properties;
    }

    /**
     * @return array<PhpFunctionParameter>
     */
    public function all() : array
    {
        return $this->arguments;
    }
}
