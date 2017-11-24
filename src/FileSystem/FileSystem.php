<?php

namespace kristijorgji\DbToPhp\FileSystem;

use DirectoryIterator;
use kristijorgji\DbToPhp\FileSystem\Exceptions\FileSystemException;

class FileSystem implements FileSystemInterface
{
    /**
     * @param string $path
     * @return string
     */
    public function readFile(string $path) : string
    {
        return file_get_contents($path);
    }

    /**
     * @param string $path
     * @param string $content
     * @return void
     * @throws FileSystemException
     */
    public function write(string $path, string $content)
    {
        try {
            fwrite(
                fopen($path, 'w'),
                $content
            );
        } catch (\Exception $e) {
            throw new FileSystemException(
                sprintf('Failed to write to %s', $path),
                -4,
                $e
            );
        }
    }

    /**
     * @param string $path
     * @return string
     * @throws FileSystemException
     */
    public function getFileExtension(string $path) : string
    {
        if (!file_exists($path)) {
            throw new FileSystemException(
                sprintf('%s does not exist!', $path)
            );
        }

        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * @param string $path
     */
    public function emptyDirectory(string $path)
    {
        foreach (new DirectoryIterator($path) as $fileInfo) {
            if(!$fileInfo->isDot()) {
                unlink($fileInfo->getPathname());
            }
        }
    }

    /**
     * @param string $path
     */
    public function deleteDirectory(string $path)
    {
        $this->emptyDirectory($path);
        rmdir($path);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function exists(string $path) : bool
    {
        return file_exists($path);
    }

    /**
     * @param string $path
     * @param bool $recursive
     */
    public function createDirectory(string $path, bool $recursive = false)
    {
        mkdir($path, 0777, $recursive);
    }
}
