<?php

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpSetterGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpSetterGenerator;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\Tests\Helpers\TestCase;

class PhpSetterGeneratorTest extends TestCase
{
    use SamplePhpProperties;

    /**
     * @dataProvider generateProvider
     * @param PhpProperty $property
     * @param PhpSetterGeneratorConfig $config
     * @param string $expected
     */
    public function testGenerate(PhpProperty $property, PhpSetterGeneratorConfig $config, string $expected)
    {
        $generator = new PhpSetterGenerator(
            $property,
            $config
        );

        $actual = $generator->generate();

        $this->assertEquals($expected, $actual);
    }

    public function generateProvider()
    {
        $expected = self::getExpected(__DIR__ . '/expected/setter_generator.txt');
        return [
            'with_annotation_and_type_hinting_fluent' => [
                $this->getSampleProperty(),
                new PhpSetterGeneratorConfig(true, true, true),
                $expected['with_annotation_and_type_hinting_fluent']
            ],
            'with_annotation_no_type_hinting_fluent' => [
                $this->getSampleProperty(),
                new PhpSetterGeneratorConfig(true, false, true),
                $expected['with_annotation_no_type_hinting_fluent']
            ],
            'no_annotation_with_type_hinting_fluent' => [
                $this->getSampleProperty(),
                new PhpSetterGeneratorConfig(false, true, true),
                $expected['no_annotation_with_type_hinting_fluent']
            ],
            'no_annotation_no_type_hinting_fluent' => [
                $this->getSampleProperty(),
                new PhpSetterGeneratorConfig(false, false, true),
                $expected['no_annotation_no_type_hinting_fluent']
            ],
            'no_annotation_no_type_hinting_not_fluent' => [
                $this->getSampleProperty(),
                new PhpSetterGeneratorConfig(false, false, false),
                $expected['no_annotation_no_type_hinting_not_fluent']
            ]
        ];
    }
}
