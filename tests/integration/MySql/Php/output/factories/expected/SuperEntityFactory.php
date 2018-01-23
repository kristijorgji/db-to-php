<?php

namespace Factories\Entities;

use kristijorgji\DbToPhp\Data\AbstractEntityFactory;
use Entities\SuperEntity;

class SuperEntityFactory extends AbstractEntityFactory
{
    /**
     * @var array
     */
    protected static $fields = [
        'id',
        'event',
        'payload',
        'status',
        'super_status',
        'active',
        'file',
        'time',
        'can_be_nulled',
        'created_at',
        'updated_at',
        'new_column',
    ];

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
        self::validateData($data);
        return self::mapArrayToEntity($data, SuperEntity::class);
    }

    /**
     * @param array $data
     * @return array
     */
    public static function makeData(array $data = []) : array
    {
        self::validateData($data);
        return [
            'id' => array_key_exists('id', $data) ?
                $data['id'] : self::randomUnsignedInt64(),
            'event' => array_key_exists('event', $data) ?
                $data['event'] : self::randomString(rand(0, 50)),
            'payload' => array_key_exists('payload', $data) ?
                $data['payload'] : self::randomString(rand(0, 64)),
            'status' => array_key_exists('status', $data) ?
                $data['status'] : self::chooseRandomString('jaru', 'naru', 'daru'),
            'super_status' => array_key_exists('super_status', $data) ?
                $data['super_status'] : self::chooseRandomString('1', '4', '111'),
            'active' => array_key_exists('active', $data) ?
                $data['active'] : self::randomBoolean(),
            'file' => array_key_exists('file', $data) ?
                $data['file'] : self::randomString(rand(0, 64)),
            'time' => array_key_exists('time', $data) ?
                $data['time'] : self::randomString(rand(0, 64)),
            'can_be_nulled' => array_key_exists('can_be_nulled', $data) ?
                $data['can_be_nulled'] : self::randomInt32(),
            'created_at' => array_key_exists('created_at', $data) ?
                $data['created_at'] : self::randomDate('Y-m-d H:i:s'),
            'updated_at' => array_key_exists('updated_at', $data) ?
                $data['updated_at'] : self::randomDate('Y-m-d H:i:s'),
            'new_column' => array_key_exists('new_column', $data) ?
                $data['new_column'] : self::randomBoolean(),
        ];
    }
}
