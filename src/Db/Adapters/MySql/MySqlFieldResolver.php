<?php

namespace kristijorgji\DbToPhp\Db\Adapters\MySql;

use kristijorgji\DbToPhp\Db\Adapters\MySql\Exceptions\UnknownMySqlTypeException;
use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\DbToPhp\Db\Fields\BoolField;
use kristijorgji\DbToPhp\Db\Fields\DateField;
use kristijorgji\DbToPhp\Db\Fields\DecimalField;
use kristijorgji\DbToPhp\Db\Fields\DoubleField;
use kristijorgji\DbToPhp\Db\Fields\EnumField;
use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Db\Fields\FloatField;
use kristijorgji\DbToPhp\Db\Fields\IntegerField;
use kristijorgji\DbToPhp\Db\Fields\JsonField;
use kristijorgji\DbToPhp\Db\Fields\TextField;
use kristijorgji\DbToPhp\Db\Fields\YearField;
use kristijorgji\DbToPhp\Support\StringCollection;

class MySqlFieldResolver
{
    /**
     * @param string $name
     * @param string $type
     * @param string $null
     * @return Field
     * @throws UnknownMySqlTypeException
     */
    public function resolveField(string $name, string $type, string $null) : Field
    {
        $nullable = $null === 'NO' ? false : true;

        if (preg_match('#^enum\((.*)\)#i', $type, $out)) {
            return new EnumField(
                $name,
                $nullable,
                $this->resolveEnumAllowedTypes($out[1])
            );
        }

        if (preg_match('#^char\((\\d+)\)#i', $type, $captured)
            || preg_match('#^varchar\((\\d+)\)#i', $type, $captured)) {
            return new TextField($name, $nullable, $captured[1]);
        }

        if (preg_match('#^(tiny|small|medium|long)*text#i', $type)
            || preg_match('#^(tiny|small|medium|long)*blob#i', $type)) {
            return new TextField($name, $nullable);
        }

        if (preg_match('#^json$#i', $type)) {
            return new JsonField($name, $nullable);
        }

        if (preg_match('#^year\((\d+)\)#i', $type, $captured)) {
            return new YearField($name, $nullable, $captured[1]);
        }

        // TODO time type
        if (preg_match('#^time$#i', $type)) {
            return new TextField($name, $nullable);
        }

        // TODO datetime type
        if (preg_match('#^datetime#i', $type)) {
            return new TextField($name, $nullable);
        }

        if (preg_match('#^timestamp#i', $type)) {
            return new DateField($name, $nullable, DateField::MYSQL_TIMESTAMP);
        }

        // TODO date type
        if (preg_match('#^date#i', $type)) {
            return new TextField($name, $nullable);
        }

        if (preg_match('#^binary\((\\d+)\)#i', $type, $captured)
            || preg_match('#^varbinary\((\\d+)\)#i', $type, $captured)) {
            return new BinaryField($name, $nullable, $captured[1]);
        }

        if (($type == 'tinyint(1)' || preg_match('#(?=^bit)#i', $type))) {
            return new BoolField($name, $nullable);
        } else if (preg_match('#^(tiny|small|medium|big)*int\(\d+\)( unsigned)?#i', $type, $captured)) {
            $signed = empty($captured[2]) ? true : false;
            $length = $this->getIntLength(empty($captured[1])  ? 'int' : $captured[1]);
            return new IntegerField($name, $nullable, $length, $signed);
        }

        if (preg_match('#(?=^float)#i', $type)) {
            return new FloatField($name, $nullable);
        }

        if (preg_match('#(?=^double)|(?=^real)|(?=^fixed)#', $type)) {
            return new DoubleField($name, $nullable);
        }

        if (preg_match('#^decimal\((\d+),(\d+)\)( unsigned)?#', $type, $captured)) {
            $signed = empty($captured[3]) ? true : false;
            $decimalPrecision = $captured[1] - $captured[2];
            return new DecimalField($name, $nullable, $decimalPrecision, $captured[2], $signed);
        }

        /**
         * For all unsupported types so far like point, geometry etc
         * we return strings in order not to crash the application
         */
        return new TextField($name, $nullable);
    }

    /**
     * @param string $allowed
     * @return StringCollection
     */
    private function resolveEnumAllowedTypes(string $allowed) : StringCollection
    {
        $allowedLength = strlen($allowed);
        $values = [];
        $capture = false;
        $captured = '';

        for ($i = 0; $i < $allowedLength; $i++) {
            if ($allowed[$i] === '\'') {
                if ($capture) {
                    $values[] = $captured;
                    $captured = '';
                }

                $capture = !$capture;
                continue;
            }

            if ($capture) {
                $captured .= $allowed[$i];
            }
        }

        return new StringCollection(...$values);
    }

    /**
     * @param string $type
     * @return int
     * @throws \InvalidArgumentException
     */
    private function getIntLength(string $type) : int
    {
        switch ($type) {
            case 'tiny':
                return 8;
            case 'small':
                return 16;
            case 'medium':
                return 24;
            case 'int':
                return 32;
            case 'big':
                return '64';
            default:
                throw new \InvalidArgumentException('Unrecognized mysql integer type ' . $type);
        }
    }
}
