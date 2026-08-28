<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db\Fields;

class BinaryField extends Field
{
    /**
     * @internal param $signed
     */
    public function __construct(string $name, bool $nullable, private int $lengthInBytes = 64)
    {
        parent::__construct($name, $nullable);
    }

    public function getLengthInBytes(): int
    {
        return $this->lengthInBytes;
    }
}
