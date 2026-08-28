<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Managers;

use kristijorgji\DbToPhp\Managers\Exceptions\GenerateException;

interface ManagerContract
{
    /**
     * @throws GenerateException
     */
    public function generateEntities() : GenerateResponse;

    /**
     * @throws GenerateException
     */
    public function generateFactories() : GenerateResponse;
}
