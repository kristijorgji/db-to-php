<?php

namespace Factories\Entities;

use kristijorgji\DbToPhp\Data\AbstractEntityFactory;
use Entities\BinariusEntity;

class BinariusEntityFactory extends AbstractEntityFactory
{
    /**
     * @var array
     */
    protected static $fields = [
        'file',
        'image',
    ];

    /**
     * @param array $data
     * @return BinariusEntity
     */
    public static function make(array $data = []) : BinariusEntity
    {
        return self::makeFromData(self::makeData($data));
    }

    /**
     * @param array $data
     * @return BinariusEntity
     */
    public static function makeFromData(array $data) : BinariusEntity
    {
        self::validateData($data);
        return self::mapArrayToEntity($data, BinariusEntity::class);
    }

    /**
     * @param array $data
     * @return array
     */
    public static function makeData(array $data = []) : array
    {
        self::validateData($data);
        return [
            'file' => array_key_exists('file', $data) ?
                $data['file'] : self::randomString(rand(0, 1)),
            'image' => array_key_exists('image', $data) ?
                $data['image'] : self::randomString(rand(0, 64)),
        ];
    }
}
