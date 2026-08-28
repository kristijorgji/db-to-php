<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\FileSystem;

use kristijorgji\DbToPhp\FileSystem\Exceptions\FileSystemException;

interface FileSystemInterface
{
    public function readFile(string $path) : string;

    /**
     * @throws FileSystemException
     */
    public function write(string $path, string $content): void;

    /**
     * @throws FileSystemException
     */
    public function getFileExtension(string $path) : string;

    public function emptyDirectory(string $path): void;

    public function deleteDirectory(string $path): void;

    public function exists(string $path) : bool;

    public function createDirectory(string $path, bool $recursive = false): void;
}
