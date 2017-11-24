<?php

namespace kristijorgji\UnitTests\FileSystem;

use kristijorgji\DbToPhp\FileSystem\Exceptions\FileSystemException;
use kristijorgji\DbToPhp\FileSystem\FileSystem;
use kristijorgji\Tests\Helpers\TestCase;

class FileSystemTest extends TestCase
{
    /**
     * @var FileSystem
     */
    private $fileSystem;

    public function setUp()
    {
        $this->fileSystem = new FileSystem();
    }

    public function testCreateAndDeleteDirectory()
    {
        $path = __DIR__ . '/test';
        $this->fileSystem->createDirectory($path);
        $this->fileSystem->deleteDirectory($path);
    }

    public function testReadFile()
    {
        $expected = 'dfadsfd
';
        $this->assertEquals(
            $expected,
            $this->fileSystem->readFile(__DIR__ . '/testfile.img')
        );
    }
    public function testWrite_on_non_existing_directory()
    {
        $this->expectException(FileSystemException::class);
        $this->fileSystem->write(__DIR__. '/'. self::randomString(40) . '/' . self::randomString(), '232');
    }

    public function testGetFileExtension()
    {
        $this->assertEquals(
            'img',
            $this->fileSystem->getFileExtension(__DIR__ . '/testfile.img')
        );
    }

    public function testGetFileExtension_file_does_not_exist()
    {
        $this->expectException(FileSystemException::class);
        $this->fileSystem->getFileExtension(self::randomString());
    }
}
