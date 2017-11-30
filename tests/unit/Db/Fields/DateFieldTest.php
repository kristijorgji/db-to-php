<?php

namespace kristijorgji\UnitTests\Db\Fields;

use kristijorgji\DbToPhp\Db\Fields\DateField;
use kristijorgji\Tests\Helpers\TestCase;

class DateFieldTest extends TestCase
{
    public function test_wrong_format()
    {
        $this->expectException(\InvalidArgumentException::class);
        new DateField(self::randomString(), self::faker()->boolean(), self::randomString());
    }
}
