<?php

return [
    'typeHint' => true,
    'databaseDriver' => kristijorgji\DbToPhp\DatabaseDrivers::MYSQL,
    'connection' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'test_db_to_php',
        'username' => 'root',
        'password' => 'Test123@',
    ],
    'entities' => [
        'includeTables' => ['*'],
        'tableToEntityClassName' => [
            'test' => 'SuperEntity'
        ],
        'outputDirectory' => __DIR__ . '/output/entities/actual',
        'namespace' => 'Entities',
        'includeAnnotations' => true,
        'includeSetters' => true,
        'includeGetters' => true,
        'fluentSetters' => true,
        'properties' => [
            'accessModifier' => \kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers::PRIVATE
        ],
        'trackChangesFor' => []
    ],
    'factories' => [
        'includeTables' => ['*'],
        'tableToEntityFactoryClassName' => [
            'test' => 'SuperEntityFactory'
        ],
        'outputDirectory' => __DIR__ . '/output/factories/actual',
        'namespace' => 'Factories\Entities',
        'includeAnnotations' => true
    ]
];
