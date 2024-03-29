<?php

return [
    'typeHint' => true,
    'databaseDriver' => kristijorgji\DbToPhp\DatabaseDrivers::MYSQL,
    'connection' => [
        'host' => '192.168.66.7',
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
        'outputDirectory' => 'Entities',
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
        'outputDirectory' => 'Factories/Entities',
        'namespace' => 'Factories\Entities',
        'includeAnnotations' => true
    ]
];
