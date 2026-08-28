<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Config;

use kristijorgji\DbToPhp\Config\Exceptions\ConfigParserException;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use function strtolower;

class ConfigFactory
{
    public function __construct(private FileSystemInterface $fileSystem)
    {
    }

    /**
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
