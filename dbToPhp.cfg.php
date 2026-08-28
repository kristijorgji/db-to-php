<?php declare(strict_types = 1);

use kristijorgji\DbToPhp\DatabaseDrivers;
use kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers;

return [
    'typeHint' => true,
    'databaseDriver' => DatabaseDrivers::MYSQL,
    'connection' => [
        'host' => getenv('DB_HOST') !== false ? getenv('DB_HOST') : '127.0.0.1',
        'port' => getenv('DB_PORT') !== false ? (int) getenv('DB_PORT') : 3306,
        'database' => getenv('DB_DATABASE') !== false ? getenv('DB_DATABASE') : 'test_db_to_php',
        'username' => getenv('DB_USERNAME') !== false ? getenv('DB_USERNAME') : 'root',
        'password' => getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : 'Test123@',
    ],
    'entities' => [
        'includeTables' => ['*'],
        'tableToEntityClassName' => [
            'test' => 'SuperEntity',
        ],
        'outputDirectory' => 'Entities',
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
        'outputDirectory' => 'Factories/Entities',
        'namespace' => 'Factories\Entities',
        'includeAnnotations' => true,
    ],
];
