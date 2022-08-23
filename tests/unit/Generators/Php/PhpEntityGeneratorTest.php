<?php

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpClassGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpGetterGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpPropertyGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpSetterGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityGenerator;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityGeneratorConfig;
use kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers;
use kristijorgji\DbToPhp\Support\StringCollection;
use kristijorgji\Tests\Helpers\TestCase;

class PhpEntityGeneratorTest extends TestCase
{
    use SamplePhpProperties;

    /**
     * @dataProvider generateProvider
     * @param PhpEntityGeneratorConfig $config
     * @param string $expected
     */
    public function testGenerate(PhpEntityGeneratorConfig $config, string $expected)
    {
        $properties = $this->getSampleProperties();
        $entityGenerator = new PhpEntityGenerator($config, $properties);
        $actual = $entityGenerator->generate();

        $this->assertEquals($expected, $actual);
    }

    public function generateProvider()
    {
        $expected = self::getExpected(__DIR__ . '/expected/entity_generator.txt');

        $phpClassGeneratorConfig = new PhpClassGeneratorConfig(
            'MyApp\Entities',
            'TestEntity',
            new StringCollection(...[]),
            null
        );

        return [
            'no_setters_no_getters' => [
                new PhpEntityGeneratorConfig(
                    $phpClassGeneratorConfig,
                    false,
                    false,
                    new PhpSetterGeneratorConfig(true, true, true),
                    new PhpGetterGeneratorConfig(true, true),
                    new PhpPropertyGeneratorConfig(
                        true,
                        false
                    ),
                    false
                ),
                $expected['no_setters_no_getters']
            ],
            'with_getters_and_setters' => [
                new PhpEntityGeneratorConfig(
                    $phpClassGeneratorConfig,
                    true,
                    true,
                    new PhpSetterGeneratorConfig(true, true, true),
                    new PhpGetterGeneratorConfig(true, true),
                    new PhpPropertyGeneratorConfig(
                        true,
                        false
                    ),
                    false
                ),
                $expected['with_getters_and_setters']
            ],
            'only_getters' => [
                new PhpEntityGeneratorConfig(
                    $phpClassGeneratorConfig,
                    false,
                    true,
                    new PhpSetterGeneratorConfig(true, true, true),
                    new PhpGetterGeneratorConfig(true, true),
                    new PhpPropertyGeneratorConfig(
                        true,
                        false
                    ),
                    false
                ),
                $expected['only_getters']
            ],
            'only_setters' => [
                new PhpEntityGeneratorConfig(
                    $phpClassGeneratorConfig,
                    true,
                    false,
                    new PhpSetterGeneratorConfig(true, true, true),
                    new PhpGetterGeneratorConfig(true, true),
                    new PhpPropertyGeneratorConfig(
                        true,
                        false
                    ),
                    false
                ),
                $expected['only_setters']
            ],
            'track_changes' => [
                new PhpEntityGeneratorConfig(
                    new PhpClassGeneratorConfig(
                        'MyApp\Entities',
                        'TestEntity',
                        new StringCollection(...[]),
                        null
                    ),
                    true,
                    true,
                    new PhpSetterGeneratorConfig(true, true, true),
                    new PhpGetterGeneratorConfig(true, true),
                    new PhpPropertyGeneratorConfig(
                        true,
                        false
                    ),
                    true
                ),
                $expected['track_changes_no_properties']
            ]
        ];
    }
}
