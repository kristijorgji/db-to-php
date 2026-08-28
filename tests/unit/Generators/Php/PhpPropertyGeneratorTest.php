<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpPropertyGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpPropertyGenerator;
use kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use kristijorgji\Tests\Helpers\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class PhpPropertyGeneratorTest extends TestCase
{
    use SamplePhpProperties;

    /**     * @param PhpProperty $property
     */
    #[DataProvider('generateProvider')]
    public function testGenerate(
        PhpProperty $property,
        PhpPropertyGeneratorConfig $config,
        string $expected,
    ): void {
        $actual = (new PhpPropertyGenerator($property, $config))->generate();
        $this->assertEquals($expected, $actual);
    }

    public static function generateProvider(): array
    {
        $expected = self::getExpected(__DIR__ . '/expected/property_generator.txt');

        return [
            'no_annotations' => [
                self::getSampleProperty(),
                new PhpPropertyGeneratorConfig(false, false),
                $expected['no_annotations'],
            ],
            'with_annotations_nullable' => [
                self::getSampleProperty(),
                new PhpPropertyGeneratorConfig(true, false),
                $expected['with_annotations_nullable'],
            ],
            'with_annotations_not_nullable' => [
                 new PhpProperty(
                     PhpAccessModifiers::PROTECTED,
                     new PhpType(PhpTypes::INTEGER, false),
                     'employeeAge',
                 ),
                new PhpPropertyGeneratorConfig(true, false),
                $expected['with_annotations_not_nullable'],
            ],
            'with_type_hints_no_annotation' => [
                new PhpProperty(
                    PhpAccessModifiers::PROTECTED,
                    new PhpType(PhpTypes::STRING, true),
                    'secretValue',
                ),
                new PhpPropertyGeneratorConfig(false, true),
                $expected['with_type_hints_no_annotation'],
            ],
        ];
    }
}
