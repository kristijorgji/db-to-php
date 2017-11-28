<?php

namespace kristijorgji\DbToPhp\Managers;

interface ManagerContract
{
    /**
     * @return void
     */
    public function generateEntities();

    /**
     * @return void
     */
    public function generateFactories();
}
