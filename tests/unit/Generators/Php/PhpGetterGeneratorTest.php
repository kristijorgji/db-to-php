<?php

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpGetterGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpGetterGenerator;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\Tests\Helpers\TestCase;

class PhpGetterGeneratorTest extends TestCase
{
    use SamplePhpProperties;

    /**
     * @dataProvider generateProvider
     * @param PhpProperty $property
     * @param PhpGetterGeneratorConfig $config
     * @param string $expected
     */
    public function testGenerate(PhpProperty $property, PhpGetterGeneratorConfig $config, string $expected)
    {
        $generator = new PhpGetterGenerator(
            $property,
            $config
        );

        $actual = $generator->generate();

        $this->assertEquals($expected, $actual);
    }

    public function generateProvider()
    {
        $expected = self::getExpected(__DIR__ . '/expected/getter_generator.txt');
        return [
            'with_annotation_and_type_hinting' => [
                $this->getSampleProperty(),
                new PhpGetterGeneratorConfig(true, true),
                $expected['with_annotation_and_type_hinting']
            ],
            'with_annotation_no_type_hinting' => [
                $this->getSampleProperty(),
                new PhpGetterGeneratorConfig(true, false),
                $expected['with_annotation_no_type_hinting']
            ],
            'no_annotation_with_type_hinting' => [
                $this->getSampleProperty(),
                new PhpGetterGeneratorConfig(false, true),
                $expected['no_annotation_with_type_hinting']
            ],
            'no_annotation_no_type_hinting' => [
                $this->getSampleProperty(),
                new PhpGetterGeneratorConfig(false, false),
                $expected['no_annotation_no_type_hinting']
            ]
        ];
    }
}
