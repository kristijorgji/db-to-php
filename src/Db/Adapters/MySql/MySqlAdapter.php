<?php

namespace kristijorgji\DbToPhp\Db\Adapters\MySql;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Db\Fields\FieldsCollection;
use kristijorgji\DbToPhp\Db\Table;
use kristijorgji\DbToPhp\Db\TablesCollection;
use PDO;

class MySqlAdapter implements DatabaseAdapterInterface
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $dbName;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var MySqlFieldResolver
     */
    private $fieldResolver;

    /**
     * @param string $host
     * @param int $port
     * @param string $dbName
     * @param string $username
     * @param string $password
     */
    public function __construct(
        string $host,
        int $port,
        string $dbName,
        string $username,
        string $password
    )
    {
        $this->host = $host;
        $this->port = $port;
        $this->dbName = $dbName;
        $this->username = $username;
        $this->password = $password;
        $this->pdo = new PDO(
            "mysql:host={$this->host}:{$this->port};dbname={$this->dbName};charset=utf8",
            $this->username,
            $this->password
        );

        $this->fieldResolver = new MySqlFieldResolver();
    }

    public function __destruct()
    {
        $this->pdo = null;
    }

    /**
     * @return TablesCollection
     */
    public function getTables() : TablesCollection
    {
        $query = "SHOW TABLES FROM  " . $this->dbName;
        $statement = $this->pdo->query($query);
        $result = $statement->fetchAll(\PDO::FETCH_COLUMN);

        return new TablesCollection(... array_map(function ($tableName) {
            return new Table($tableName);
        }, $result));
    }

    /**
     * @param string $tableName
     * @return FieldsCollection
     */
    public function getFields(string $tableName): FieldsCollection
    {
        $query = "SHOW COLUMNS FROM $tableName";
        $statement = $this->pdo->query($query);
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return new FieldsCollection(... array_map(function ($field) {
                return $this->fieldResolver->resolveField(
                    $field['Field'],
                    $field['Type'],
                    $field['Null']
                );
            }, $result)
        );
    }
}
