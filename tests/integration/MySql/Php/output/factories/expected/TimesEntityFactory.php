<?php

namespace Factories\Entities;

use kristijorgji\DbToPhp\Data\AbstractEntityFactory;
use Entities\TimesEntity;

class TimesEntityFactory extends AbstractEntityFactory
{
    /**
     * @var array
     */
    protected $fields = [
        'birthday_year',
        'birthday_time',
        'column_3',
        'logged_in',
        'registered_date',
    ];

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
        self::validateData($data);
        return self::mapArrayToEntity($data, TimesEntity::class);
    }

    /**
     * @param array $data
     * @return array
     */
    public static function makeData(array $data = []) : array
    {
        self::validateData($data);
        return [
            'birthday_year' => array_key_exists('birthday_year', $data) ?
                $data['birthday_year'] : self::randomYear(4),
            'birthday_time' => array_key_exists('birthday_time', $data) ?
                $data['birthday_time'] : self::randomString(rand(0, 64)),
            'column_3' => array_key_exists('column_3', $data) ?
                $data['column_3'] : self::randomString(rand(0, 64)),
            'logged_in' => array_key_exists('logged_in', $data) ?
                $data['logged_in'] : self::randomDate('Y-m-d H:i:s'),
            'registered_date' => array_key_exists('registered_date', $data) ?
                $data['registered_date'] : self::randomString(rand(0, 64)),
        ];
    }
}
