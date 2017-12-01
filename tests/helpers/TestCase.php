<?php

namespace kristijorgji\Tests\Helpers;

use DirectoryIterator;

class TestCase extends \PHPUnit_Framework_TestCase
{
    use TestHelpers;

    protected function assertFoldersContentMatch(string $expectedDirectory, string $actualDirectory)
    {
        $expectedFiles = [];

        foreach (new DirectoryIterator($expectedDirectory) as $fileInfo) {
            if(!$fileInfo->isDot()) {
                $expectedFiles[] = $fileInfo->getFilename();
            }
        }

        foreach (new DirectoryIterator($actualDirectory) as $fileInfo) {
            if(!$fileInfo->isDot()) {
                $this->assertTrue(
                    in_array($fileInfo->getFilename(), $expectedFiles),
                    sprintf(
                        'File %s was not expected in directory %s !',
                        $fileInfo->getFilename(),
                        $actualDirectory
                    )
                );
            }
        }

        foreach ($expectedFiles as $expectedEntityClassName) {
            $expectedFilePath = $expectedDirectory . '/' . $expectedEntityClassName;
            $actualFilePath = $actualDirectory . '/' . $expectedEntityClassName;
            $this->assertEquals(
                file_get_contents($expectedFilePath),
                file_get_contents($actualFilePath),
                sprintf('File %s is different then %s !', $expectedFilePath, $actualFilePath)
            );
        }
    }
}
