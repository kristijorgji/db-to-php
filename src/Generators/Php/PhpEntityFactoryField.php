<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php;


class PhpEntityFactoryField
{
    public function __construct(
        private readonly string $dbFieldName,
        private readonly string $resolvingCall,
    ) {
    }

    public function getDbFieldName(): string
    {
        return $this->dbFieldName;
    }

    public function getResolvingCall(): string
    {
        return $this->resolvingCall;
    }
}
