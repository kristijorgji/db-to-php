<?php

namespace kristijorgji\DbToPhp\Mappers\Types\Php;

use kristijorgji\DbToPhp\DatabaseDrivers;

class PhpTypeMapperFactory
{
    /**
     * @param string $databaseDriver
     * @return PhpTypeMapperInterface
     * @throws \InvalidArgumentException
     */
    public function get(string $databaseDriver) : PhpTypeMapperInterface
    {
        switch ($databaseDriver) {
            case DatabaseDrivers::MYSQL:
                return new MySqlPhpTypeMapper();
            default:
                throw new \InvalidArgumentException('Invalid database driver!');
        }
    }

}
