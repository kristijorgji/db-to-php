<?php

namespace Factories\Entities;

use kristijorgji\DbToPhp\Data\AbstractEntityFactory;
use Entities\Test2Entity;

class Test2EntityFactory extends AbstractEntityFactory
{
    /**
     * @var array
     */
    protected static $fields = [
        'id',
        'name',
        'surname',
        'isWorking',
        'salary',
        'discount',
        'new_column',
        'dddd',
        'binaeraylk',
        'f',
    ];

    /**
     * @param array $data
     * @return Test2Entity
     */
    public static function make(array $data = []): Test2Entity
    {
        return self::makeFromData(self::makeData($data));
    }

    /**
     * @param array $data
     * @return Test2Entity
     */
    public static function makeFromData(array $data): Test2Entity
    {
        self::validateData($data);
        return self::mapArrayToEntity($data, Test2Entity::class);
    }

    /**
     * @param array $data
     * @return array
     */
    public static function makeData(array $data = []): array
    {
        self::validateData($data);
        return [
            'id' => array_key_exists('id', $data) ?
                $data['id'] : self::randomInt32(),
            'name' => array_key_exists('name', $data) ?
                $data['name'] : self::randomString(rand(0, 50)),
            'surname' => array_key_exists('surname', $data) ?
                $data['surname'] : self::randomString(rand(0, 64)),
            'isWorking' => array_key_exists('isWorking', $data) ?
                $data['isWorking'] : self::randomBoolean(),
            'salary' => array_key_exists('salary', $data) ?
                $data['salary'] : self::randomFloat(),
            'discount' => array_key_exists('discount', $data) ?
                $data['discount'] : self::randomFloat(4),
            'new_column' => array_key_exists('new_column', $data) ?
                $data['new_column'] : self::randomFloat(),
            'dddd' => array_key_exists('dddd', $data) ?
                $data['dddd'] : self::randomString(rand(0, 64)),
            'binaeraylk' => array_key_exists('binaeraylk', $data) ?
                $data['binaeraylk'] : self::randomString(rand(0, 1)),
            'f' => array_key_exists('f', $data) ?
                $data['f'] : self::randomString(rand(0, 64)),
        ];
    }
}
