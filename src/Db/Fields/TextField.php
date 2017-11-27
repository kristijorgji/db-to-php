<?php

namespace kristijorgji\DbToPhp\Db\Fields;

class TextField extends Field
{
    /**
     * @var int
     */
    private $lengthInBytes;

    /**
     * @param string $name
     * @param string $type
     * @param bool $nullable
     * @param int $lengthInBytes
     * @internal param $signed
     */
    public function __construct(string $name, string $type, bool $nullable, int $lengthInBytes = 64)
    {
        parent::__construct($name, $type, $nullable);
        $this->lengthInBytes = $lengthInBytes;
    }

    /**
     * @return int
     */
    public function getLengthInBytes(): int
    {
        return $this->lengthInBytes;
    }
}
