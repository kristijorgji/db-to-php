<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db\Fields;

class TextField extends Field
{
    /**
     * @internal param $signed
     */
    public function __construct(string $name, bool $nullable, private readonly int $lengthInBytes = 64)
    {
        parent::__construct($name, $nullable);
    }

    public function getLengthInBytes(): int
    {
        return $this->lengthInBytes;
    }
}
