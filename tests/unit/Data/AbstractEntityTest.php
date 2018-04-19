<?php

namespace kristijorgji\UnitTests\Data;

use kristijorgji\Tests\Helpers\TestCase;

class AbstractEntityTest extends TestCase
{
    public function testAll()
    {
        $testEntity = new TestPseudoModelEntity();
        $this->assertFalse($testEntity->isDirty());
        $this->assertEmpty($testEntity->dirtyFields());

        $testEntity->setId(3);
        $testEntity->setId(4);
        $testEntity->setIsWorking(true);
        $this->assertTrue($testEntity->isDirty());
        $this->assertEquals(
            [
                'id' => 4,
                'is_working' => true
            ],
            $testEntity->dirtyFields()
        );

        $testEntity->sync();
        $this->assertFalse($testEntity->isDirty());
        $this->assertEmpty($testEntity->dirtyFields());

        $testEntity->setIsWorking(false);
        $testEntity->setSurname('Jorgji');
        $this->assertTrue($testEntity->isDirty());
        $this->assertEquals(
            [
                'is_working' => false,
                'surname' => 'Jorgji'
            ],
            $testEntity->dirtyFields()
        );
    }
}
