<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Rules\Php\PhpType;

class PhpEntityFactoryField
{
    /**
     * @var string
     */
    private $dbFieldName;

    /**
     * @var string
     */
    private $resolvingCall;

    /**
     * @param string $dbFieldName
     * @param string $resolvingCall
     */
    public function __construct(
        string $dbFieldName,
        string $resolvingCall
    )
    {
        $this->dbFieldName = $dbFieldName;
        $this->resolvingCall = $resolvingCall;
    }

    /**
     * @return string
     */
    public function getDbFieldName(): string
    {
        return $this->dbFieldName;
    }

    /**
     * @return string
     */
    public function getResolvingCall(): string
    {
        return $this->resolvingCall;
    }
}
