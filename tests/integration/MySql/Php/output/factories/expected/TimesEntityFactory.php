<?php

namespace Factories\Entities;

use Entities\TimesEntity;

class TimesEntityFactory extends AbstractEntityFactory
{
    /**
     * @param array $data
     * @return TimesEntity
     */
    public static function make(array $data = []) : TimesEntity
    {
        return self::makeFromData(self::makeData($data));
    }

    /**
     * @param array $data
     * @return TimesEntity
     */
    public static function makeFromData(array $data) : TimesEntity
    {
        return self::mapArrayToEntity($data, TimesEntity::class);
    }

    /**
     * @param array $data
     * @return array
     */
    public static function makeData(array $data = []) : array
    {
        return [
            'birthday_year' => $data['birthday_year'] ?? self::randomYear(4),
            'birthday_time' => $data['birthday_time'] ?? self::randomString(rand(0, 64)),
            'column_3' => $data['column_3'] ?? self::randomString(rand(0, 64)),
            'logged_in' => $data['logged_in'] ?? self::randomDate('Y-m-d H:i:s'),
            'registered_date' => $data['registered_date'] ?? self::randomString(rand(0, 64)),
        ];
    }
}
