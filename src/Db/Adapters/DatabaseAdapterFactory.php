<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db\Adapters;

use kristijorgji\DbToPhp\DatabaseDrivers;
use kristijorgji\DbToPhp\Db\Adapters\Exceptions\InvalidDatabaseDriverException;
use kristijorgji\DbToPhp\Db\Adapters\MySql\MySqlAdapter;
use function sprintf;

class DatabaseAdapterFactory
{
    /**
     * @throws InvalidDatabaseDriverException
     */
    public function get(string $databaseDriver, array $config) : DatabaseAdapterInterface
    {
        switch ($databaseDriver) {
            case DatabaseDrivers::MYSQL:
                return new MySqlAdapter(
                    $config['host'],
                    (int) $config['port'],
                    $config['database'],
                    $config['username'],
                    $config['password'],
                );
            default:
                throw new InvalidDatabaseDriverException(
                    sprintf('Invalid database driver: %s !', $databaseDriver),
                );
        }
    }
}
