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
        return match ($databaseDriver) {
            DatabaseDrivers::MYSQL => new PhpTypeMapper,
            default => throw new InvalidArgumentException('Invalid database driver!'),
        };
    }
}
