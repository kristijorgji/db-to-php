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
}
