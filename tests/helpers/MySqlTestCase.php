<?php

namespace kristijorgji\Tests\Helpers;

use PDO;

abstract class MySqlTestCase extends TestCase
{
    /**
     * @var array
     */
    protected static $mysqlConnection = [
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'test_db_to_php',
        'username' => 'root',
        'password' => 'Test123@',
    ];

    /**
     * @var PDO
     */
    protected static $pdo;

    /**
     * @return void
     */
    public static function setUpBeforeClass()
    {
        $dsn = sprintf(
            'mysql:host=%s:%s;charset=utf8',
            static::$mysqlConnection['host'],
            static::$mysqlConnection['port']
        );

        self::$pdo = new PDO(
            $dsn,
            static::$mysqlConnection['username'],
            static::$mysqlConnection['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );

        self::initializeTestDatabase();
    }

    /**
     * @return void
     */
    public static function tearDownAfterClass()
    {
        self::dropTestDatabase();
        self::$pdo = null;
    }

    /**
     * @return string
     */
    abstract public static function getDumpPath() : string;

    /**
     * @return void
     */
    private static function initializeTestDatabase()
    {
        self::$pdo->exec( sprintf(
            'CREATE DATABASE IF NOT EXISTS %s;',
            static::$mysqlConnection['database']
        ));

        self::$pdo->exec( sprintf(
            'USE %s;',
            static::$mysqlConnection['database']
        ));

        $sql = file_get_contents(static::getDumpPath());
        self::$pdo->exec($sql);
    }

    /**
     * @return void
     */
    private  static function dropTestDatabase()
    {
        self::$pdo->exec( sprintf(
            'DROP DATABASE %s;',
            static::$mysqlConnection['database']
        ));
    }

    /**
     * @return array
     */
    private static function fetchMySqlStatements() : array
    {
        $sql = file_get_contents(static::getDumpPath());
        $sqlLength = strlen($sql);

        $statements = [];

        $insideStringGroup = false;
        $insideLineComment = false;
        $commentGroup = false;
        $capturedStatement = '';

        for ($i = 0; $i < $sqlLength; ++$i) {
            if ($sql[$i] === '-' && (($i + 1) < $sqlLength && $sql[$i + 1] === '-')) {
                $insideLineComment = true;
                continue;
            }

            if ($insideLineComment) {
                if ($sql[$i] !== PHP_EOL) {
                    continue;
                } else {
                    $insideLineComment = false;
                }
            }

            if ($sql[$i] === '\'' && ($i-1 >= 0 && $sql[$i-1] !== '\\')) {
                $insideStringGroup = ! $insideStringGroup;
            }

            if ($insideStringGroup) {
                continue;
            }
        }
    }
}
