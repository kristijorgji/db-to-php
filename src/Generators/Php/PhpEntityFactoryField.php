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
    private $entityPropertyName;

    /**
     * @var PhpType
     */
    private $type;

    /**
     * @var string
     */
    private $resolvingCall;

    /**
     * @param string $dbFieldName
     * @param string $entityPropertyName
     * @param PhpType $type
     * @param string $resolvingCall
     */
    public function __construct(
        string $dbFieldName,
        string $entityPropertyName,
        PhpType $type,
        string $resolvingCall
    )
    {
        $this->dbFieldName = $dbFieldName;
        $this->entityPropertyName = $entityPropertyName;
        $this->type = $type;
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
    public function getEntityPropertyName(): string
    {
        return $this->entityPropertyName;
    }

    /**
     * @return PhpType
     */
    public function getType(): PhpType
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getResolvingCall(): string
    {
        return $this->resolvingCall;
    }
}
