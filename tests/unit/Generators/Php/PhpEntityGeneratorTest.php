<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpClassGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpGetterGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpPropertyGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpSetterGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityGenerator;
use kristijorgji\DbToPhp\Support\StringCollection;
use kristijorgji\Tests\Helpers\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class PhpEntityGeneratorTest extends TestCase
{
    use SamplePhpProperties;

    /**     * @param PhpEntityGeneratorConfig $config
     */
    #[DataProvider('generateProvider')]
    public function testGenerate(PhpEntityGeneratorConfig $config, string $expected): void
    {
        $properties = $this->getSampleProperties();
        $entityGenerator = new PhpEntityGenerator($config, $properties);
        $actual = $entityGenerator->generate();

        $this->assertSame($expected, $actual);
    }

    public static function generateProvider(): array
    {
        $expected = self::getExpected(__DIR__ . '/expected/entity_generator.txt');

        $phpClassGeneratorConfig = new PhpClassGeneratorConfig(
            'MyApp\Entities',
            'TestEntity',
            new StringCollection(...[]),
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
                        false,
                    ),
                    false,
                ),
                $expected['no_setters_no_getters'],
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
                        false,
                    ),
                    false,
                ),
                $expected['with_getters_and_setters'],
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
                        false,
                    ),
                    false,
                ),
                $expected['only_getters'],
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
                        false,
                    ),
                    false,
                ),
                $expected['only_setters'],
            ],
            'track_changes' => [
                new PhpEntityGeneratorConfig(
                    new PhpClassGeneratorConfig(
                        'MyApp\Entities',
                        'TestEntity',
                        new StringCollection(...[]),
                    ),
                    true,
                    true,
                    new PhpSetterGeneratorConfig(true, true, true),
                    new PhpGetterGeneratorConfig(true, true),
                    new PhpPropertyGeneratorConfig(
                        true,
                        false,
                    ),
                    true,
                ),
                $expected['track_changes_no_properties'],
            ],
        ];
    }
}
