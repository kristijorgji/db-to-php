<?php

return [
    'typeHint' => true,
    'databaseDriver' => kristijorgji\DbToPhp\DatabaseDrivers::MYSQL,
    'connection' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'db_to_php',
        'username' => 'root',
        'password' => 'Test123@',
    ],
    'entities' => [
        'includeTables' => ['*'],
        'tableToEntityClassName' => [
            'test' => 'SuperEntity'
        ],
        'outputDirectory' => 'Entities',
        'namespace' => 'Entities',
        'includeAnnotations' => true,
        'includeSetters' => true,
        'includeGetters' => true,
        'fluentSetters' => true,
        'properties' => [
            'accessModifier' => \kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers::PRIVATE
        ]
    ]
];
