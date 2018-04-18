<?php

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpSetterGeneratorConfig
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
     * @var bool
     */
    private $isFluent;

    /**
     * @param bool $includeAnnotations
     * @param bool $typeHint
     * @param bool $isFluent
     */
    public function __construct(bool $includeAnnotations, bool $typeHint, bool $isFluent)
    {
        $this->includeAnnotations = $includeAnnotations;
        $this->typeHint = $typeHint;
        $this->isFluent = $isFluent;
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

    /**
     * @return boolean
     */
    public function isFluent(): bool
    {
        return $this->isFluent;
    }
}
