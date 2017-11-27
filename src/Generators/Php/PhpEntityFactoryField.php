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
     * @var int|null
     */
    private $lengthLimit;

    /**
     * @var bool|null
     */
    private $signed;

    /**
     * @param string $dbFieldName
     * @param string $entityPropertyName
     * @param PhpType $type
     * @param int|null $lengthLimit
     * @param bool|null $signed
     */
    public function __construct(
        string $dbFieldName,
        string $entityPropertyName,
        PhpType $type,
        ?int $lengthLimit,
        ?bool $signed = null
    )
    {
        $this->dbFieldName = $dbFieldName;
        $this->entityPropertyName = $entityPropertyName;
        $this->type = $type;
        $this->lengthLimit = $lengthLimit;
        $this->signed = $signed;
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
     * @return int|null
     */
    public function getLengthLimit() : ?int
    {
        return $this->lengthLimit;
    }

    /**
     * @return bool|null
     */
    public function isSigned() : ?bool
    {
        return $this->signed;
    }
}
