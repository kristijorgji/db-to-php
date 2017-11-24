<?php

namespace kristijorgji\DbToPhp\FileSystem;

use kristijorgji\DbToPhp\FileSystem\Exceptions\FileSystemException;

interface FileSystemInterface
{
    /**
     * @param string $path
     * @return string
     */
    public function readFile(string $path) : string;

    /**
     * @param string $path
     * @param string $content
     * @return void
     * @throws FileSystemException
     */
    public function write(string $path, string $content);

    /**
     * @param string $path
     * @return string
     * @throws FileSystemException
     */
    public function getFileExtension(string $path) : string;

    /**
     * @param string $path
     */
    public function emptyDirectory(string $path);

    /**
     * @param string $path
     */
    public function deleteDirectory(string $path);

    /**
     * @param string $path
     * @return bool
     */
    public function exists(string $path) : bool;

    /**
     * @param string $path
     * @param bool $recursive
     */
    public function createDirectory(string $path, bool $recursive = false);
}
