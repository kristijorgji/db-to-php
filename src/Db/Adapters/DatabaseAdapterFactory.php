<?php

namespace kristijorgji\DbToPhp\Db\Adapters;

use kristijorgji\DbToPhp\DatabaseDrivers;
use kristijorgji\DbToPhp\Db\Adapters\Exceptions\InvalidDatabaseDriverException;

class DatabaseAdapterFactory
{
    /**
     * @param string $databaseDriver
     * @param array $config
     * @return DatabaseAdapterInterface
     * @throws InvalidDatabaseDriverException
     */
    public function get(string $databaseDriver, array $config) : DatabaseAdapterInterface
    {
        switch ($databaseDriver) {
            case DatabaseDrivers::MYSQL:
                return new MySqlAdapter(
                    $config['host'],
                    $config['port'],
                    $config['database'],
                    $config['username'],
                    $config['password']
                );
            default:
                throw new InvalidDatabaseDriverException(
                    sprintf('Invalid database driver: %s !', $databaseDriver)
                );
        }
    }
}
