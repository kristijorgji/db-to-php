<?php

namespace kristijorgji\DbToPhp\Managers;

use kristijorgji\DbToPhp\Managers\Exceptions\GenerateException;

interface ManagerContract
{
    /**
     * @return GenerateResponse
     * @throws GenerateException
     */
    public function generateEntities() : GenerateResponse;

    /**
     * @return GenerateResponse
     * @throws GenerateException
     */
    public function generateFactories() : GenerateResponse;
}
