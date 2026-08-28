<?php declare(strict_types = 1);

use kristijorgji\DbToPhp\DatabaseDrivers;
use kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers;
use kristijorgji\Tests\Helpers\MySqlTestCase;

return [
    'typeHint' => true,
    'databaseDriver' => DatabaseDrivers::MYSQL,
    'connection' => MySqlTestCase::$mysqlConnection,
    'entities' => [
        'includeTables' => ['*'],
        'tableToEntityClassName' => [
            'test' => 'SuperEntity',
        ],
        'outputDirectory' => __DIR__ . '/output/entities/actual',
        'namespace' => 'Entities',
        'includeAnnotations' => true,
        'includeSetters' => true,
        'includeGetters' => true,
        'fluentSetters' => true,
        'properties' => [
            'accessModifier' => PhpAccessModifiers::PRIVATE,
        ],
        'trackChangesFor' => [],
    ],
    'factories' => [
        'includeTables' => ['*'],
        'tableToEntityFactoryClassName' => [
            'test' => 'SuperEntityFactory',
        ],
        'outputDirectory' => __DIR__ . '/output/factories/actual',
        'namespace' => 'Factories\Entities',
        'includeAnnotations' => true,
    ],
];
