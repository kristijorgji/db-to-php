<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests;

use kristijorgji\Tests\Helpers\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use function basePath;
use function camelToSnakeCase;

final class HelpersTest extends TestCase
{
    public function testGetBasePath(): void
    {
        $actual = basePath();
        $this->assertMatchesRegularExpression('#\/src\/\.\.\/$#', $actual);
    }

    /**     * @param string $input
     */
    #[DataProvider('camelToSnakeCaseProvider')]
    public function testCamelToSnakeCase(
        string $input,
        string $expected,
    ): void {
        $this->assertSame(
            $expected,
            camelToSnakeCase($input),
        );
    }

    public static function camelToSnakeCaseProvider(): array
    {
        return [
            [
                'iAmCamelCase',
                'i_am_camel_case',
            ],
            [
                'allUsers',
                'all_users',
            ],
            [
                'AAA',
                'a_a_a',
            ],
        ];
    }
}
