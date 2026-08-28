<?php

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\PhpPropertyAnnotationGenerator;
use kristijorgji\DbToPhp\Rules\Php\PhpObjectType;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use kristijorgji\Tests\Helpers\TestCase;

class PhpPropertyAnnotationGeneratorTest extends TestCase
{
    /**     * @param PhpType $type
     * @param string $expected
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('generateProvider')]
    public function testGenerate(
        PhpType $type,
        string $expected
    )
    {
        $generator = new PhpPropertyAnnotationGenerator(
            $type
        );

        $this->assertEquals(
            $expected,
            $generator->generate()
        );
    }

    public static function generateProvider()
    {
        $expected = self::getExpected(__DIR__ . '/expected/property_annotation_generator.txt');
        return [
            'array_type_not_nullable' => [
                new PhpType(
                    PhpTypes::ARRAY,
                    false
                ),
                $expected['array_type_not_nullable']
            ],
            'array_type_nullable' => [
                new PhpType(
                    PhpTypes::ARRAY,
                    true
                ),
                $expected['array_type_nullable']
            ],
            'object_type' => [
                new PhpObjectType(
                    false,
                    'Gari'
                ),
                $expected['class_type_not_nullable']
            ]
        ];
    }
}
