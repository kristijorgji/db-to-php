<?php

namespace kristijorgji\DbToPhp\Managers\Exceptions;

use kristijorgji\DbToPhp\Managers\GenerateResponse;

class GenerateException extends \Exception
{
    /**
     * @var GenerateResponse
     */
    private $partialResponse;

    /**
     * @param string $message
     * @param \Exception $previous
     * @param GenerateResponse $partialResponse
     */
    public function __construct(string $message, \Exception $previous, GenerateResponse $partialResponse)
    {
        parent::__construct($message, -177, $previous);
        $this->partialResponse = $partialResponse;
    }

    /**
     * @return GenerateResponse
     */
    public function getPartialResponse(): GenerateResponse
    {
        return $this->partialResponse;
    }
}
