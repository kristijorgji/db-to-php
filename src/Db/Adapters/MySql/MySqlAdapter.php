<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db\Adapters\MySql;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Db\Fields\FieldsCollection;
use kristijorgji\DbToPhp\Db\Table;
use kristijorgji\DbToPhp\Db\TablesCollection;
use PDO;
use function array_map;

class MySqlAdapter implements DatabaseAdapterInterface
{
    private ?PDO $pdo;
    private readonly MySqlFieldResolver $fieldResolver;

    public function __construct(
        private readonly string $host,
        private readonly int $port,
        private readonly string $dbName,
        private readonly string $username,
        private readonly string $password,
    ) {
        $this->pdo = new PDO(
            "mysql:host={$this->host}:{$this->port};dbname={$this->dbName};charset=utf8",
            $this->username,
            $this->password,
        );

        $this->fieldResolver = new MySqlFieldResolver;
    }

    public function __destruct()
    {
        $this->pdo = null;
    }

    public function getTables() : TablesCollection
    {
        $query = "SHOW TABLES FROM  " . $this->dbName;
        $statement = $this->pdo->query($query);
        $result = $statement->fetchAll(PDO::FETCH_COLUMN);

        return new TablesCollection(... array_map(fn($tableName) => new Table($tableName), $result));
    }

    public function getFields(string $tableName): FieldsCollection
    {
        $query = "SHOW COLUMNS FROM $tableName";
        $statement = $this->pdo->query($query);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return new FieldsCollection(... array_map(fn($field) => $this->fieldResolver->resolveField(
            $field['Field'],
            $field['Type'],
            $field['Null'],
        ), $result));
    }
}
