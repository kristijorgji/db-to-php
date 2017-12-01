<?php

namespace Factories\Entities;

use kristijorgji\DbToPhp\Data\AbstractEntityFactory;
use Entities\SuperEntity;

class SuperEntityFactory extends AbstractEntityFactory
{
    /**
     * @param array $data
     * @return SuperEntity
     */
    public static function make(array $data = []) : SuperEntity
    {
        return self::makeFromData(self::makeData($data));
    }

    /**
     * @param array $data
     * @return SuperEntity
     */
    public static function makeFromData(array $data) : SuperEntity
    {
        return self::mapArrayToEntity($data, SuperEntity::class);
    }

    /**
     * @param array $data
     * @return array
     */
    public static function makeData(array $data = []) : array
    {
        return [
            'id' => $data['id'] ?? self::randomUnsignedInt64(),
            'event' => $data['event'] ?? self::randomString(rand(0, 50)),
            'payload' => $data['payload'] ?? self::randomString(rand(0, 64)),
            'status' => $data['status'] ?? self::chooseRandomString('jaru', 'naru', 'daru'),
            'super_status' => $data['super_status'] ?? self::chooseRandomString('1', '4', '111'),
            'active' => $data['active'] ?? self::randomBoolean(),
            'file' => $data['file'] ?? self::randomString(rand(0, 64)),
            'time' => $data['time'] ?? self::randomString(rand(0, 64)),
            'can_be_nulled' => $data['can_be_nulled'] ?? self::randomInt32(),
            'created_at' => $data['created_at'] ?? self::randomDate('Y-m-d H:i:s'),
            'updated_at' => $data['updated_at'] ?? self::randomDate('Y-m-d H:i:s'),
            'new_column' => $data['new_column'] ?? self::randomBoolean(),
        ];
    }
}
