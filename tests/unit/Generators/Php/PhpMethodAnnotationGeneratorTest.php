<?php

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\PhpMethodAnnotationGenerator;
use kristijorgji\DbToPhp\Rules\Php\PhpFunctionParameter;
use kristijorgji\DbToPhp\Rules\Php\PhpFunctionParametersCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use kristijorgji\Tests\Helpers\TestCase;

class PhpMethodAnnotationGeneratorTest extends TestCase
{
    /**
     * @dataProvider generateProvider
     * @param PhpFunctionParametersCollection $parameters
     * @param PhpType|null $returnType
     * @param bool $typeHint
     * @param string $expected
     */
    public function testGenerate(
        PhpFunctionParametersCollection $parameters,
        ?PhpType $returnType,
        bool $typeHint,
        string $expected
    )
    {
        $generator = new PhpMethodAnnotationGenerator(
            $parameters,
            $returnType,
            $typeHint
        );

        $actual = $generator->generate();

        $this->assertEquals($expected, $actual);
    }

    public function generateProvider()
    {
        $expected = self::getExpected(__DIR__ . '/expected/method_annotation_generator.txt');
        return [
            'void_return_type_type_hinting' => [
                new PhpFunctionParametersCollection(... [
                    new PhpFunctionParameter('test', new PhpType(new PhpTypes(PhpTypes::BOOL), true))
                ]),
                null,
                true,
                $expected['void_return_type_type_hinting']
            ]
        ];
    }

}
