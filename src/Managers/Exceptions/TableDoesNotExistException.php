<?php

namespace kristijorgji\DbToPhp\Managers\Exceptions;

class TableDoesNotExistException extends \Exception
{
    private $tableName;

    /**
     * @param string $tableName
     */
    public function __construct(string $tableName)
    {
        parent::__construct(
            sprintf('The included table %s does not exist', $tableName),
            -77,
            null
        );

        $this->tableName = $tableName;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }
}
