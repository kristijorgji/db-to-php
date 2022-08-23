<?php

namespace kristijorgji\UnitTests\Support;

use kristijorgji\DbToPhp\Support\Enum;
use kristijorgji\Tests\Helpers\TestCase;

class EnumTest extends TestCase
{
    /**
     * @var TestEnum
     */
    private $testEnum;

    public function setUp(): void
    {
        $this->testEnum = new TestEnum(TestEnum::ANOTHER_TEST);
    }

    public function testWrongConstruct()
    {
        $this->expectException(\InvalidArgumentException::class);
        $t = new TestEnum('this does not exist in constants');
        echo (string) $t;
    }

    public function testGetValueAsString()
    {
        $this->assertEquals('another test', (string) $this->testEnum);
    }

    public function testGetSelfKey()
    {
        $this->assertEquals('ANOTHER_TEST', $this->testEnum->getSelfKey());
    }

    public function testGetSelfKey_on_error()
    {
        $optionsProperty = $this->getPrivateProperty(Enum::class, 'options');
        $initialValue = $optionsProperty->getValue($this->testEnum);
        $changedValue = $initialValue;
        $changedValue[TestEnum::class]['ANOTHER_TEST'] = self::randomString();
        $optionsProperty->setValue($this->testEnum, $changedValue);

        try {
            $this->testEnum->getSelfKey();
        } catch (\Exception $e) {
            $this->assertInstanceOf(\Exception::class, $e);
            $optionsProperty->setValue($initialValue);
        }
    }

    public function testGetKey()
    {
        $this->assertEquals('TEST_4', TestEnum::getKey(6));
    }

    public function testGetKey_from_non_existing_value()
    {
        $this->expectException(\InvalidArgumentException::class);
        TestEnum::getKey('adfasdffdfafdasf');
    }

    public function testToArray()
    {
        $expected = [
            'TEST' => 0,
            'TEST_2'  => 2,
            'TEST_4'  => 6,
            'ANOTHER_TEST' => 'another test'
        ];

        $this->assertEquals($expected, TestEnum::toArray());
    }

    public function testGetValues()
    {
        $expected = [
            0,
            2,
            6,
            'another test'
        ];

        $this->assertEquals($expected, TestEnum::getValues());
    }
}
