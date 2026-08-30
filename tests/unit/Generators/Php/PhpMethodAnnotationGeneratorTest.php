<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\PhpMethodAnnotationGenerator;
use kristijorgji\DbToPhp\Rules\Php\PhpFunctionParameter;
use kristijorgji\DbToPhp\Rules\Php\PhpFunctionParametersCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use kristijorgji\Tests\Helpers\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class PhpMethodAnnotationGeneratorTest extends TestCase
{
    /**     * @param PhpFunctionParametersCollection $parameters
     */
    #[DataProvider('generateProvider')]
    public function testGenerate(
        PhpFunctionParametersCollection $parameters,
        ?PhpType $returnType,
        string $expected,
    ): void {
        $generator = new PhpMethodAnnotationGenerator(
            $parameters,
            $returnType,
        );

        $actual = $generator->generate();

        $this->assertSame($expected, $actual);
    }

    public static function generateProvider(): array
    {
        $expected = self::getExpected(__DIR__ . '/expected/method_annotation_generator.txt');
        return [
            'void_return_type_type_hinting' => [
                new PhpFunctionParametersCollection(... [
                    new PhpFunctionParameter('test', new PhpType(PhpTypes::BOOL, true)),
                ]),
                null,
                $expected['void_return_type_type_hinting'],
            ],
        ];
    }
}
