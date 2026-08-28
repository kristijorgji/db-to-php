<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Managers;

class GenerateResponse
{
    /**
     * @var array<string>
     */
    private array $generatedFilesPaths = [];

    public function addPath(string $path): void
    {
        $this->generatedFilesPaths[] = $path;
    }

    public function getPaths() : array
    {
        return $this->generatedFilesPaths;
    }
}
