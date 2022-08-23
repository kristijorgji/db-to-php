<?php

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpPropertyGeneratorConfig
{
    /**
     * @var bool
     */
    private $includeAnnotations;

    /**
     * @var bool
     */
    private $typed;

    /**
     * @param bool $includeAnnotations
     * @param bool $typed
     */
    public function __construct(
        bool $includeAnnotations,
        bool $typed
    )
    {
        $this->includeAnnotations = $includeAnnotations;
        $this->typed = $typed;
    }

    public function shouldIncludeAnnotations(): bool
    {
        return $this->includeAnnotations;
    }

    public function shouldBeTypeHinted(): bool
    {
        return $this->typed;
    }
}
