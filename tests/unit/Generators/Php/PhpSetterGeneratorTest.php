<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpSetterGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpSetterGenerator;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\Tests\Helpers\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class PhpSetterGeneratorTest extends TestCase
{
    use SamplePhpProperties;

    /**     * @param PhpProperty $property
     */
    #[DataProvider('generateProvider')]
    public function testGenerate(PhpProperty $property, PhpSetterGeneratorConfig $config, string $expected): void
    {
        $generator = new PhpSetterGenerator(
            $property,
            $config,
        );

        $actual = $generator->generate();

        $this->assertSame($expected, $actual);
    }

    public static function generateProvider(): array
    {
        $expected = self::getExpected(__DIR__ . '/expected/setter_generator.txt');
        return [
            'with_annotation_and_type_hinting_fluent' => [
                self::getSampleProperty(),
                new PhpSetterGeneratorConfig(true, true, true),
                $expected['with_annotation_and_type_hinting_fluent'],
            ],
            'with_annotation_no_type_hinting_fluent' => [
                self::getSampleProperty(),
                new PhpSetterGeneratorConfig(true, false, true),
                $expected['with_annotation_no_type_hinting_fluent'],
            ],
            'no_annotation_with_type_hinting_fluent' => [
                self::getSampleProperty(),
                new PhpSetterGeneratorConfig(false, true, true),
                $expected['no_annotation_with_type_hinting_fluent'],
            ],
            'no_annotation_no_type_hinting_fluent' => [
                self::getSampleProperty(),
                new PhpSetterGeneratorConfig(false, false, true),
                $expected['no_annotation_no_type_hinting_fluent'],
            ],
            'no_annotation_no_type_hinting_not_fluent' => [
                self::getSampleProperty(),
                new PhpSetterGeneratorConfig(false, false, false),
                $expected['no_annotation_no_type_hinting_not_fluent'],
            ],
        ];
    }
}
