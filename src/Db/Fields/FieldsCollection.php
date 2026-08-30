<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db\Fields;

class FieldsCollection
{
    /**
     * @var array<Field>
     */
    private array $fields = [];

    public function __construct(Field ... $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return array<Field>
     */
    public function all() : array
    {
        return $this->fields;
    }
}
