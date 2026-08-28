<?php

namespace kristijorgji\UnitTests;

use kristijorgji\Tests\Helpers\TestCase;

class HelpersTest extends TestCase
{
    public function testGetBasePath()
    {
        $actual = basePath();
        $this->assertTrue(
            preg_match('#\/src\/\.\.\/$#', $actual) == true
        );
    }

    /**     * @param string $input
     * @param string $expected
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('camelToSnakeCaseProvider')]
    public function testCamelToSnakeCase(
        string $input,
        string $expected
    )
    {
        $this->assertEquals(
            $expected,
            camelToSnakeCase($input)
        );
    }

    public static function camelToSnakeCaseProvider()
    {
        return [
            [
                'iAmCamelCase',
                'i_am_camel_case'
            ],
            [
                'allUsers',
                'all_users'
            ],
            [
                'AAA',
                'a_a_a'
            ]
        ];
    }
}
