<?php

namespace kristijorgji\UnitTests\Data;

use kristijorgji\Tests\Helpers\TestCase;

class AbstractEntityTest extends TestCase
{
    public function testGetDirty()
    {
        // TODO

        $testEntity = new TestPseudoModelEntity();
        $this->assertFalse($testEntity->isDirty());
        $this->assertEmpty($testEntity->getDirty());

        $testEntity->setId(3);
        $testEntity->setIsWorking(true);
        $this->assertTrue($testEntity->isDirty());
        $this->assertEquals(
            [
                'id' => 3,
                'is_working' => true
            ],
            $testEntity->getDirty()
        );
    }
}
