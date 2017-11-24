<?php

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpPropertyGeneratorConfig
{
    /**
     * @var bool
     */
    private $includeAnnotations;

    /**
     * @param bool $includeAnnotations
     */
    public function __construct(
        bool $includeAnnotations
    )
    {
        $this->includeAnnotations = $includeAnnotations;
    }

    /**
     * @return boolean
     */
    public function shouldIncludeAnnotations(): bool
    {
        return $this->includeAnnotations;
    }
}
