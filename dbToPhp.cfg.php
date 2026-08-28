<?php

return [
    'typeHint' => true,
    'databaseDriver' => kristijorgji\DbToPhp\DatabaseDrivers::MYSQL,
    'connection' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int) (getenv('DB_PORT') ?: 3306),
        'database' => getenv('DB_DATABASE') ?: 'test_db_to_php',
        'username' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : 'Test123@',
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
