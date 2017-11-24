<?php

namespace kristijorgji\DbToPhp\Config;

use kristijorgji\DbToPhp\Config\Exceptions\ConfigParserException;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;

class ConfigFactory
{
    /**
     * @var FileSystemInterface
     */
    private $fileSystem;

    /**
     * @param FileSystemInterface $fileSystem
     */
    public function __construct(FileSystemInterface $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param string $path
     * @return array
     * @throws ConfigParserException
     */
    public function get(string $path) : array
    {
        $extension = strtolower($this->fileSystem->getFileExtension($path));

        switch ($extension) {
            case 'php':
                return require $path;
            default:
                throw new ConfigParserException('Only configurations in php format are supported for now');
        }
    }
}
