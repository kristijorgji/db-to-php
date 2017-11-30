<?php

namespace Factories\Entities;

use Entities\Test2Entity;

class Test2EntityFactory extends AbstractEntityFactory
{
    /**
     * @param array $data
     * @return Test2Entity
     */
    public static function make(array $data = []) : Test2Entity
    {
        return self::makeFromData(self::makeData($data));
    }

    /**
     * @param array $data
     * @return Test2Entity
     */
    public static function makeFromData(array $data) : Test2Entity
    {
        return self::mapArrayToEntity($data, Test2Entity::class);
    }

    /**
     * @param array $data
     * @return array
     */
    public static function makeData(array $data = []) : array
    {
        return [
            'id' => $data['id'] ?? self::randomInt32(),
            'name' => $data['name'] ?? self::randomString(rand(0, 50)),
            'surname' => $data['surname'] ?? self::randomString(rand(0, 64)),
            'isWorking' => $data['isWorking'] ?? self::randomBoolean(),
            'salary' => $data['salary'] ?? self::randomFloat(),
            'discount' => $data['discount'] ?? self::randomFloat(4),
            'new_column' => $data['new_column'] ?? self::randomFloat(),
            'dddd' => $data['dddd'] ?? self::randomString(rand(0, 64)),
            'binaeraylk' => $data['binaeraylk'] ?? self::randomString(rand(0, 1)),
            'f' => $data['f'] ?? self::randomString(rand(0, 64)),
        ];
    }
}
