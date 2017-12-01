<?php

namespace kristijorgji\DbToPhp\Managers;

class GenerateResponse
{
    /**
     * @var string[]
     */
    private $generatedFilesPaths = [];

    /**
     * @param string $path
     */
    public function addPath(string $path)
    {
        $this->generatedFilesPaths[] = $path;
    }

    /**
     * @return array
     */
    public function getPaths() : array
    {
        return $this->generatedFilesPaths;
    }
}
