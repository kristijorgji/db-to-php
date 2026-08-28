<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\FileSystem;

use DirectoryIterator;
use kristijorgji\DbToPhp\FileSystem\Exceptions\FileSystemException;
use function fclose;
use function file_exists;
use function file_get_contents;
use function fopen;
use function fwrite;
use function mkdir;
use function pathinfo;
use function rmdir;
use function sprintf;
use function unlink;
use const PATHINFO_EXTENSION;

class FileSystem implements FileSystemInterface
{
    public function readFile(string $path) : string
    {
        return file_get_contents($path);
    }

    /**
     * @throws FileSystemException
     */
    public function write(string $path, string $content): void
    {
        $handle = @fopen($path, 'w');

        if ($handle === false) {
            throw new FileSystemException(
                sprintf('Failed to write to %s', $path),
                -4,
            );
        }

        try {
            fwrite($handle, $content);
        } finally {
            fclose($handle);
        }
    }

    /**
     * @throws FileSystemException
     */
    public function getFileExtension(string $path) : string
    {
        if (!file_exists($path)) {
            throw new FileSystemException(
                sprintf('%s does not exist!', $path),
            );
        }

        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public function emptyDirectory(string $path): void
    {
        foreach (new DirectoryIterator($path) as $fileInfo) {
            if(!$fileInfo->isDot()) {
                unlink($fileInfo->getPathname());
            }
        }
    }

    public function deleteDirectory(string $path): void
    {
        $this->emptyDirectory($path);
        rmdir($path);
    }

    public function exists(string $path) : bool
    {
        return file_exists($path);
    }

    public function createDirectory(string $path, bool $recursive = false): void
    {
        mkdir($path, 0777, $recursive);
    }
}
