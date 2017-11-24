<?php

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpPropertyGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpPropertyGenerator;
use kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use kristijorgji\Tests\Helpers\TestCase;

class PhpPropertyGeneratorTest extends TestCase
{
    use SamplePhpProperties;

    /**
     * @dataProvider generateProvider
     * @param PhpProperty $property
     * @param PhpPropertyGeneratorConfig $config
     * @param string $expected
     */
    public function testGenerate(
        PhpProperty $property,
        PhpPropertyGeneratorConfig $config,
        string $expected
    )
    {
        $actual = (new PhpPropertyGenerator($property, $config))->generate();
        $this->assertEquals($expected, $actual);
    }

    public function generateProvider()
    {
        $expected = self::getExpected(__DIR__ . '/expected/property_generator.txt');

        return [
            'no_annotations' => [
                $this->getSampleProperty(),
                new PhpPropertyGeneratorConfig(false),
                $expected['no_annotations']
            ],
            'with_annotations_nullable' => [
                $this->getSampleProperty(),
                new PhpPropertyGeneratorConfig(true),
                $expected['with_annotations_nullable']
            ],
            'with_annotations_not_nullable' => [
                 new PhpProperty(
                     new PhpAccessModifiers(PhpAccessModifiers::PROTECTED),
                     new PhpType(new PhpTypes(PhpTypes::INTEGER), false),
                     'employeeAge'
                 ),
                new PhpPropertyGeneratorConfig(true),
                $expected['with_annotations_not_nullable']
            ]
        ];
    }
}
