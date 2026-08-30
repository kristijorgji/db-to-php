<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Managers\Exceptions;

use Exception;
use kristijorgji\DbToPhp\Managers\GenerateResponse;
use Throwable;

class GenerateException extends Exception
{
    public function __construct(
        string $message,
        Throwable $previous,
        private readonly GenerateResponse $partialResponse,
    ) {
        parent::__construct($message, -177, $previous);
    }

    public function getPartialResponse(): GenerateResponse
    {
        return $this->partialResponse;
    }
}
