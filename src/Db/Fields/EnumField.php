<?php

namespace kristijorgji\DbToPhp\Db\Fields;

use kristijorgji\DbToPhp\Support\StringCollection;

class EnumField extends Field
{
    /**
     * @var StringCollection
     */
    private $allowedValues;

    /**
     * @param string $name
     * @param string $type
     * @param bool $nullable
     * @param StringCollection $allowedValues
     * @internal param $lengthInBits
     * @internal param $signed
     */
    public function __construct(string $name, string $type, bool $nullable, StringCollection $allowedValues)
    {
        parent::__construct($name, $type, $nullable);
        $this->allowedValues = $allowedValues;
    }

    /**
     * @return StringCollection
     */
    public function getAllowedValues(): StringCollection
    {
        return $this->allowedValues;
    }
}
