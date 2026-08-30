<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php;

class PhpEntityFactoryFieldsCollection
{
    /**
     * @var array<PhpEntityFactoryField>
     */
    private array $fields = [];

    public function __construct(PhpEntityFactoryField ... $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return array<PhpEntityFactoryField>
     */
    public function all() : array
    {
        return $this->fields;
    }
}
