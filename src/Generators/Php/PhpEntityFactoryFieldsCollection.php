<?php

namespace kristijorgji\DbToPhp\Generators\Php;

class PhpEntityFactoryFieldsCollection
{
    /**
     * @var PhpEntityFactoryField[]
     */
    private $fields = [];

    /**
     * @param PhpEntityFactoryField[] $fields
     */
    public function __construct(PhpEntityFactoryField... $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return PhpEntityFactoryField[]
     */
    public function all() : array
    {
        return $this->fields;
    }
}
