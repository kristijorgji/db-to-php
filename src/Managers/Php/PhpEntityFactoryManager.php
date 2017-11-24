<?php

namespace kristijorgji\DbToPhp\Managers\Php;

class PhpEntityFactoryManager extends AbstractPhpManager
{
    /**
     * @return void
     */
    public function generateFactories()
    {
        $tables = $this->filterTables(
            $this->databaseAdapter->getTables(),
            $this->config['factories']['includeTables']
        );
        foreach ($tables->all() as $table) {
            $this->generateFactory($table->getName());
        }
    }

    /**
     * @param string $tableName
     * @return void
     */
    public function generateFactory(string $tableName)
    {

    }
}
