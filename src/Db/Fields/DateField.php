<?php

namespace kristijorgji\DbToPhp\Db\Fields;

class DateField extends Field
{
    const MYSQL_TIMESTAMP = 'Y-m-d H:i:s';

    private $formats = [
        'Y-m-d H:i:s'
    ];

    /**
     * @var string
     */
    private $format;

    /**
     * @param string $name
     * @param string $nullable
     * @param string $format
     */
    public function __construct(string $name, string $nullable, string $format)
    {
        if (!in_array($format, $this->formats)) {
            throw new \InvalidArgumentException(
                sprintf('Format %s is not in the allowed formats', $format)
            );
        }

        parent::__construct($name, $nullable);
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }
}
