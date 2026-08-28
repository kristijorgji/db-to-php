<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\FileSystem;

use kristijorgji\DbToPhp\FileSystem\Exceptions\FileSystemException;
use kristijorgji\DbToPhp\FileSystem\FileSystem;
use kristijorgji\Tests\Helpers\TestCase;

class FileSystemTest extends TestCase
{
    private FileSystem $fileSystem;

    protected function setUp(): void
    {
        $this->fileSystem = new FileSystem;
    }

    public function testCreateAndDeleteDirectory(): void
    {
        $path = __DIR__ . '/test';
        $this->fileSystem->createDirectory($path);
        $this->fileSystem->deleteDirectory($path);
        self::assertFalse(
            $this->fileSystem->exists($path),
        );
    }

    public function testReadFile(): void
    {
        $expected = 'dfadsfd
';
        $this->assertEquals(
            $expected,
            $this->fileSystem->readFile(__DIR__ . '/testfile.img'),
        );
    }

    public function testWrite_on_non_existing_directory(): void
    {
        $this->expectException(FileSystemException::class);
        $this->fileSystem->write(__DIR__. '/'. self::randomString(40) . '/' . self::randomString(), '232');
    }

    public function testGetFileExtension(): void
    {
        $this->assertEquals(
            'img',
            $this->fileSystem->getFileExtension(__DIR__ . '/testfile.img'),
        );
    }

    public function testGetFileExtension_file_does_not_exist(): void
    {
        $this->expectException(FileSystemException::class);
        $this->fileSystem->getFileExtension(self::randomString());
    }
}
