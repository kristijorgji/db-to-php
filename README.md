# db-to-php

A framework for automating PHP code generation based on database metadata.

1. Create entity classes based on your database tables.
2. (Coming soon) Create factories for automatic generation of entities with random data (useful for unit tests and populating your database)
3. (Coming soon) Create repositories for writing/reading these entities
4. (Coming soon) Working to support different database drivers aside from MySql

# Table of Contents

- [Installation](#installation)
- [Generate Entities](#generate-entities)

## Installation

```sh
composer require kristijorgji/db-to-php
```

Run the following command to initialize dbToPhp
```sh
vendor/bin/dbToPhp init
```
This command will create in your project root folder the config file `dbToPhp.cfg.php`
You need to edit that to your desired settings.

## Usage

### Generate Entities

First make sure to have setup correctly your database connection and credentials
in the config file that is generated after the installation `dbToPhp.cfg.php`.

If you want the result code to be generated for php that supports typehinting and return types
(also nullable like ?string) set typeHint key to true.

The other options are self explanatory. Change anything you like under the entities key in the config.

```php
[
    'typeHint' => true,
    'databaseDriver' => 'mysql,
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
]
```

Then just run from your terminal

```sh
vendor/bin/dbToPhp generate:entities
```

#### Example execution

Table schema:

![table schema](https://github.com/kristijorgji/db-to-php/blob/master/docs/samples/entities/definition.jpg?raw=true "Table schema")

Entity code in PHP (please note that class name, inclusion of annonation etc anything can be customized in the configuration file):

https://github.com/kristijorgji/db-to-php/blob/master/docs/samples/entities/SuperEntity.php

The name of the class is SuperEntity because I set that in the config file. I set that the table with name test will have Entity class name SuperEntity. Anything can be customized.
