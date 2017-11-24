<?php

namespace kristijorgji\DbToPhp\Managers;

interface ManagerContract
{
    /**
     * @return void
     */
    public function generateEntities();

    /**
     * @param string $tableName
     * @return void
     */
    public function generateEntity(string $tableName);

    /**
     * @return void
     */
    public function generateFactories();

    /**
     * @param string $tableName
     * @return void
     */
    public function generateFactory(string $tableName);
}
