# db-to-php

A framework for automating PHP code generation for working with databases.

Many times we need data object classes corresponding to different database tables.
For testing and populating the databases with dummy data we also require factories
which generate dummy/fake data for those tables.

A lot of time is spent doing those tasks manually, so I decided to automate the process.
Not only entities and factories are created by this library, but the original type is also detected
and used in the classes and in the factory values generator.

For now the library supports only MySql as a database driver.

1. Create entity classes based on your database tables.
2. Create factories for automatic generation of entities with random data, or just random data for tables
3. (Coming soon) Create repositories for writing/reading these entities
4. (Coming soon) Working to support different database drivers aside from MySql

# Table of Contents

- [Installation](#installation)
- [Config](#config)
- [Usage](#usage)
    - [Generate Entities](#generate-entities)
        - [Instructions](#generate-entities-instructions)
        - [Example execution](#example-entity-generation)
    - [Generate Factories](#generate-factories)
        - [Instructions](#generate-factories-instructions)
        - [Example execution](#example-factory-generation)
- [License](#license)

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

## Config

The config and it's keys try to be as much self explanatory as possible.
Example config:

```php
<?php

return [
    'typeHint' => true,
    'databaseDriver' => 'mysql',
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
```

You must setup the proper database credentials and driver (for now only MySql is supported).
The  

```typehint``` - if set to true the generated PHP code will use typehints in the function parameters and also as return tyeps (PHP 7 /7.1)
 
Example with ```typehint=true```:
```
/**
 * @param array $data
 * @return TestEntity
 */
public static function make(array $data = []): TestEntity
{
    return self::makeFromData(self::makeData($data));
}
```
 
Example with ```typehint=false```:

```
/**
 * @param array $data
 * @return TestEntity
 */
public static function make()
{
    return self::makeFromData(self::makeData($data));
}
``` 

## Usage

### Generate Entities

Features
* Detect database field type and use corresponding php type for the property
* Detect if a database field is nullable and generate corresponding properties and methods
* (Optional) You can choose custom entity class names for specific tables
* You can generate entities for all the tables or only for those that you specify
* You can use annotations (PHPDoc) or not depending on the configuration that you setup
* You can configure the access modifier of the entity properties
* You can configure if you want to generate setter methods or not and if you want them to be fluent
* You can configure if you want to generate getter methods
* You can configure if you want to generate type hinted getters/setters 

####  Generate Entities Instructions

First make sure to have setup correctly your database connection and credentials
in the config file that is generated after the installation `dbToPhp.cfg.php`.

If you want the result code to be generated for php that supports typehinting and return types
(also nullable like ?string) set typeHint key to true.

The other options are self explanatory. Change anything you like under the entities key in the config.

Below is shown only the part of the config for the entities generation, you can see a full configuration example in the [config](#config) section.

```php
[
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

#### Example entity generation

In my demo setup I only had selected the table below with the following MySql schema:
```sql
CREATE TABLE users_demo
(
    id BIGINT(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL,
    surname VARCHAR(20) NOT NULL,
    preferences JSON,
    birth_year YEAR(4) NOT NULL,
    nr_cars TINYINT(3) unsigned NOT NULL,
    salary DECIMAL(18,4) NOT NULL,
    active TINYINT(1) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);
```

The generated entity class is given below 
(please note that class name, namespace, usage of annotations, typehinting, setters/getters etc anything can be customized in the configuration file):

```php
<?php

namespace Entities;

class UsersDemoEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $surname;

    /**
     * @var string|null
     */
    private $preferences;

    /**
     * @var int
     */
    private $birthYear;

    /**
     * @var int
     */
    private $nrCars;

    /**
     * @var float
     */
    private $salary;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $surname
     * @return $this
     */
    public function setSurname(string $surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string|null $preferences
     * @return $this
     */
    public function setPreferences(?string $preferences)
    {
        $this->preferences = $preferences;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPreferences(): ?string
    {
        return $this->preferences;
    }

    /**
     * @param int $birthYear
     * @return $this
     */
    public function setBirthYear(int $birthYear)
    {
        $this->birthYear = $birthYear;
        return $this;
    }

    /**
     * @return int
     */
    public function getBirthYear(): int
    {
        return $this->birthYear;
    }

    /**
     * @param int $nrCars
     * @return $this
     */
    public function setNrCars(int $nrCars)
    {
        $this->nrCars = $nrCars;
        return $this;
    }

    /**
     * @return int
     */
    public function getNrCars(): int
    {
        return $this->nrCars;
    }

    /**
     * @param float $salary
     * @return $this
     */
    public function setSalary(float $salary)
    {
        $this->salary = $salary;
        return $this;
    }

    /**
     * @return float
     */
    public function getSalary(): float
    {
        return $this->salary;
    }

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}

```

https://github.com/kristijorgji/db-to-php/blob/master/docs/samples/entities/UsersDemoEntity.php

The name for the entity is UsersDemoEntity, but that can be customized and set in the config section 'entities'['tableToEntityClassName']
### Generate Factories

Features:

* Detect enums and generate only the allowed values
* Detect if the integers are signed or not and their ranges
* Detect the precision of the float/double/decimals and generate values up to that max precision
* Detect json fields and generates valid json
* Detects date, year and generates proper values
* Detects if the field is nullable

(See example execution for demonstration)

#### Generate Factories Instructions

The config section for the factories is similar and self explanatory like the one for the entities:

```php
'factories' => [
        'includeTables' => ['*'],
        'tableToEntityFactoryClassName' => [
            'test' => 'SuperEntityFactory'
        ],
        'outputDirectory' => 'Factories/Entities',
        'namespace' => 'Factories\Entities',
        'includeAnnotations' => true
    ]
```

After configuring the section to your preferences, execute the following to generate the factories:

```sh
vendor/bin/dbToPhp generate:factories
```

#### Example factory generation

I am using as an example the same table that I used for generating entities.

```sql
CREATE TABLE users_demo
(
    id BIGINT(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL,
    surname VARCHAR(20) NOT NULL,
    preferences JSON,
    birth_year YEAR(4) NOT NULL,
    nr_cars TINYINT(3) unsigned NOT NULL,
    salary DECIMAL(18,4) NOT NULL,
    active TINYINT(1) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);
```

The result factory is given below.
As you can notice every data generated is detailed according to the table specifications.

```php
<?php

namespace Factories\Entities;

use Entities\UsersDemoEntity;

class UsersDemoEntityFactory extends AbstractEntityFactory
{
    /**
     * @param array $data
     * @return UsersDemoEntity
     */
    public static function make(array $data = []): UsersDemoEntity
    {
        return self::makeFromData(self::makeData($data));
    }
   
    /**
     * @param array $data
     * @return UsersDemoEntity
     */
    public static function makeFromData(array $data): UsersDemoEntity
    {
        return self::mapArrayToEntity($data, UsersDemoEntity::class);
    }
 
    /**
     * @param array $data
     * @return array
     */
    public static function makeData(array $data = []): array
    {
        return [
            'id' => $data['id'] ?? self::randomInt64(),
            'name' => $data['name'] ?? self::randomString(rand(0, 20)),
            'surname' => $data['surname'] ?? self::randomString(rand(0, 20)),
            'preferences' => $data['preferences'] ?? self::randomJson(),
            'birth_year' => $data['birth_year'] ?? self::randomYear(4),
            'nr_cars' => $data['nr_cars'] ?? self::randomUnsignedInt8(),
            'salary' => $data['salary'] ?? self::randomFloat(4),
            'active' => $data['active'] ?? self::randomBoolean(),
            'created_at' => $data['created_at'] ?? self::randomDate('Y-m-d H:i:s'),
        ];
    }
}

```

## License

db-to-php is released under the MIT Licence. See the bundled LICENSE file for details.







