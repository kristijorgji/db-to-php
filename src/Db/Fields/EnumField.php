<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db\Fields;

use kristijorgji\DbToPhp\Support\StringCollection;

class EnumField extends Field
{
    /**
     * @internal param $lengthInBits
     * @internal param $signed
     */
    public function __construct(string $name, bool $nullable, private readonly StringCollection $allowedValues)
    {
        parent::__construct($name, $nullable);
    }

    public function getAllowedValues(): StringCollection
    {
        return $this->allowedValues;
    }
}
