<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpGetterGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpGetterGenerator;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\Tests\Helpers\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class PhpGetterGeneratorTest extends TestCase
{
    use SamplePhpProperties;

    /**     * @param PhpProperty $property
     */
    #[DataProvider('generateProvider')]
    public function testGenerate(PhpProperty $property, PhpGetterGeneratorConfig $config, string $expected): void
    {
        $generator = new PhpGetterGenerator(
            $property,
            $config,
        );

        $actual = $generator->generate();

        $this->assertEquals($expected, $actual);
    }

    public static function generateProvider(): array
    {
        $expected = self::getExpected(__DIR__ . '/expected/getter_generator.txt');
        return [
            'with_annotation_and_type_hinting' => [
                self::getSampleProperty(),
                new PhpGetterGeneratorConfig(true, true),
                $expected['with_annotation_and_type_hinting'],
            ],
            'with_annotation_no_type_hinting' => [
                self::getSampleProperty(),
                new PhpGetterGeneratorConfig(true, false),
                $expected['with_annotation_no_type_hinting'],
            ],
            'no_annotation_with_type_hinting' => [
                self::getSampleProperty(),
                new PhpGetterGeneratorConfig(false, true),
                $expected['no_annotation_with_type_hinting'],
            ],
            'no_annotation_no_type_hinting' => [
                self::getSampleProperty(),
                new PhpGetterGeneratorConfig(false, false),
                $expected['no_annotation_no_type_hinting'],
            ],
        ];
    }
}
