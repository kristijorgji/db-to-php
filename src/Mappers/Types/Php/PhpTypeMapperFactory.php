<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Mappers\Types\Php;

use InvalidArgumentException;
use kristijorgji\DbToPhp\DatabaseDrivers;

class PhpTypeMapperFactory
{
    /**
     * @throws InvalidArgumentException
     */
    public function get(string $databaseDriver) : PhpTypeMapperInterface
    {
        switch ($databaseDriver) {
            case DatabaseDrivers::MYSQL:
                return new PhpTypeMapper;
            default:
                throw new InvalidArgumentException('Invalid database driver!');
        }
    }
}
