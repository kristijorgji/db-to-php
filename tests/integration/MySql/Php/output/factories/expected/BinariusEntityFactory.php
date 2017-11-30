<?php

namespace Factories\Entities;

use Entities\BinariusEntity;

class BinariusEntityFactory extends AbstractEntityFactory
{
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
        return self::mapArrayToEntity($data, BinariusEntity::class);
    }

    /**
     * @param array $data
     * @return array
     */
    public static function makeData(array $data = []) : array
    {
        return [
            'file' => $data['file'] ?? self::randomString(rand(0, 1)),
            'image' => $data['image'] ?? self::randomString(rand(0, 64)),
        ];
    }
}
