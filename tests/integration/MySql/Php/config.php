<?php

return [
    'typeHint' => true,
    'databaseDriver' => kristijorgji\DbToPhp\DatabaseDrivers::MYSQL,
    'connection' => \kristijorgji\Tests\Helpers\MySqlTestCase::$mysqlConnection,
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
