<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Managers\Exceptions;

use Exception;
use function sprintf;

class TableDoesNotExistException extends Exception
{
    public function __construct(private readonly string $tableName)
    {
        parent::__construct(
            sprintf('The included table %s does not exist', $tableName),
            -77,
        );

    }

    public function getTableName(): string
    {
        return $this->tableName;
    }
}
