<?php

namespace Factories\Entities;

use kristijorgji\DbToPhp\Data\AbstractEntityFactory;
use Entities\SpecialEntity;

class SpecialEntityFactory extends AbstractEntityFactory
{
    protected static array $fields = [
        'geometry',
        'geometry_collection',
        'json',
        'line_string',
        'multilinestring',
        'point',
        'multipoint',
        'polygon',
        'multy_polygon',
    ];

    /**
     * @param array $data
     * @return SpecialEntity
     */
    public static function make(array $data = []): SpecialEntity
    {
        return self::makeFromData(self::makeData($data));
    }

    /**
     * @param array $data
     * @return SpecialEntity
     */
    public static function makeFromData(array $data): SpecialEntity
    {
        self::validateData($data);
        return self::mapArrayToEntity($data, SpecialEntity::class);
    }

    /**
     * @param array $data
     * @return array
     */
    public static function makeData(array $data = []): array
    {
        self::validateData($data);
        return [
            'geometry' => array_key_exists('geometry', $data) ?
                $data['geometry'] : self::randomString(rand(0, 64)),
            'geometry_collection' => array_key_exists('geometry_collection', $data) ?
                $data['geometry_collection'] : self::randomString(rand(0, 64)),
            'json' => array_key_exists('json', $data) ?
                $data['json'] : self::randomJson(),
            'line_string' => array_key_exists('line_string', $data) ?
                $data['line_string'] : self::randomString(rand(0, 64)),
            'multilinestring' => array_key_exists('multilinestring', $data) ?
                $data['multilinestring'] : self::randomString(rand(0, 64)),
            'point' => array_key_exists('point', $data) ?
                $data['point'] : self::randomString(rand(0, 64)),
            'multipoint' => array_key_exists('multipoint', $data) ?
                $data['multipoint'] : self::randomString(rand(0, 64)),
            'polygon' => array_key_exists('polygon', $data) ?
                $data['polygon'] : self::randomString(rand(0, 64)),
            'multy_polygon' => array_key_exists('multy_polygon', $data) ?
                $data['multy_polygon'] : self::randomString(rand(0, 64)),
        ];
    }
}
