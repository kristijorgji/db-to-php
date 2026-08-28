<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db\Fields;

use InvalidArgumentException;
use function in_array;
use function sprintf;

class DateField extends Field
{
    public const MYSQL_TIMESTAMP = 'Y-m-d H:i:s';

    private array $formats = [
        'Y-m-d H:i:s',
    ];

    public function __construct(string $name, bool $nullable, private string $format)
    {
        if (!in_array($format, $this->formats)) {
            throw new InvalidArgumentException(
                sprintf('Format %s is not in the allowed formats', $format),
            );
        }

        parent::__construct($name, $nullable);
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
