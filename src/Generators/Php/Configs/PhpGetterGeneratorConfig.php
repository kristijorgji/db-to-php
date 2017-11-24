<?php

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpGetterGeneratorConfig
{
    /**
     * @var bool
     */
    private $includeAnnotations;

    /**
     * @var bool
     */
    private $typeHint;

    /**
     * @param bool $includeAnnotations
     * @param bool $typeHint
     */
    public function __construct(bool $includeAnnotations, bool $typeHint)
    {
        $this->includeAnnotations = $includeAnnotations;
        $this->typeHint = $typeHint;
    }

    /**
     * @return boolean
     */
    public function shouldIncludeAnnotations(): bool
    {
        return $this->includeAnnotations;
    }

    /**
     * @return boolean
     */
    public function shouldTypeHint(): bool
    {
        return $this->typeHint;
    }
}
