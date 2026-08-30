<?php declare(strict_types = 1);

namespace kristijorgji\Tests\Helpers;

use DirectoryIterator;
use function file_get_contents;
use function sprintf;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use TestHelpers;

    protected function assertFoldersContentMatch(string $expectedDirectory, string $actualDirectory): void
    {
        $this->assertDirectoryExists($expectedDirectory);
        $this->assertDirectoryExists($actualDirectory);

        $expectedFiles = [];

        foreach (new DirectoryIterator($expectedDirectory) as $fileInfo) {
            if(!$fileInfo->isDot()) {
                $expectedFiles[] = $fileInfo->getFilename();
            }
        }

        foreach (new DirectoryIterator($actualDirectory) as $fileInfo) {
            if(!$fileInfo->isDot()) {
                $this->assertContains(
                    $fileInfo->getFilename(),
                    $expectedFiles,
                    sprintf(
                        'File %s was not expected in directory %s !',
                        $fileInfo->getFilename(),
                        $actualDirectory,
                    ),
                );
            }
        }

        foreach ($expectedFiles as $expectedEntityClassName) {
            $expectedFilePath = $expectedDirectory . '/' . $expectedEntityClassName;
            $actualFilePath = $actualDirectory . '/' . $expectedEntityClassName;
            $this->assertEquals(
                file_get_contents($expectedFilePath),
                file_get_contents($actualFilePath),
                sprintf('File %s is different then %s !', $expectedFilePath, $actualFilePath),
            );
        }
    }
}
