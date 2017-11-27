<?php

namespace kristijorgji\DbToPhp\Rules\Php;

class PhpFunctionParameter
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var PhpType
     */
    private $type;

    /**
     * @param string $name
     * @param PhpType $type
     */
    public function __construct(string $name, PhpType $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return PhpType
     */
    public function getType(): PhpType
    {
        return $this->type;
    }

    /**
     * @param PhpType $type
     */
    public function setType(PhpType $type)
    {
        $this->type = $type;
    }
}
