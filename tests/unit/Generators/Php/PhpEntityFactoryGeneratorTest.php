<?php

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpClassGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityFactoryGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryGenerator;
use kristijorgji\DbToPhp\Support\StringCollection;
use kristijorgji\Tests\Helpers\TestCase;

class PhpEntityFactoryGeneratorTest extends TestCase
{
    /**
     * @dataProvider generateProvider
     * @param PhpEntityFactoryGeneratorConfig $config
     * @param string $expected
     */
    public function testGenerate(PhpEntityFactoryGeneratorConfig $config, string $expected)
    {
        $entityGenerator = new PhpEntityFactoryGenerator($config);
        $actual = $entityGenerator->generate();

        $this->assertEquals($expected, $actual);
    }

    public function generateProvider()
    {
        $expected = self::getExpected(__DIR__ . '/expected/entity_factory_generator.txt');

        $phpClassGeneratorConfig = new PhpClassGeneratorConfig(
            'MyApp\Entities',
            'TestEntity',
            new StringCollection(...[]),
            null
        );

        return [
            'dd' => [
                new PhpEntityFactoryGeneratorConfig(
                    $phpClassGeneratorConfig
                ),
                $expected['dd']
            ]
        ];
    }
}