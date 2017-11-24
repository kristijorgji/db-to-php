<?php

namespace kristijorgji\DbToPhp\Managers;

abstract class ManagerContract
{
    /**
     * @return void
     */
    abstract public function generateEntities();

    /**
     * @param string $tableName
     * @return void
     */
    abstract public function generateEntity(string $tableName);
}
