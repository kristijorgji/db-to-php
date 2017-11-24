<?php

namespace kristijorgji\DbToPhp\Db;

class FieldsCollection
{
    /**
     * @var Field[]
     */
    private $fields = [];

    /**
     * @param Field[] $fields
     */
    public function __construct(Field... $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return Field[]
     */
    public function all() : array
    {
        return $this->fields;
    }
}
