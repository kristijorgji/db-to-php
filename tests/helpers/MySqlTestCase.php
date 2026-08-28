<?php declare(strict_types = 1);

namespace kristijorgji\Tests\Helpers;

use PDO;
use function file_get_contents;
use function sprintf;

abstract class MySqlTestCase extends TestCase
{
    public static array $mysqlConnection = [];
    protected static ?PDO $pdo = null;

    static function init(): void
    {
        self::$mysqlConnection = [
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'database' => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
        ];
    }

    public static function setUpBeforeClass(): void
    {
        $dsn = sprintf(
            'mysql:host=%s:%s;charset=utf8',
            static::$mysqlConnection['host'],
            static::$mysqlConnection['port'],
        );

        self::$pdo = new PDO(
            $dsn,
            static::$mysqlConnection['username'],
            static::$mysqlConnection['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ],
        );

        self::initializeTestDatabase();
    }

    public static function tearDownAfterClass(): void
    {
        self::dropTestDatabase();
        self::$pdo = null;
    }

    abstract public static function getDumpPath() : string;

    private static function initializeTestDatabase(): void
    {
        self::$pdo->exec(sprintf(
            'CREATE DATABASE IF NOT EXISTS %s;',
            static::$mysqlConnection['database'],
        ));

        self::$pdo->exec(sprintf(
            'USE %s;',
            static::$mysqlConnection['database'],
        ));

        $sql = file_get_contents(static::getDumpPath());
        self::$pdo->exec($sql);
    }

    private  static function dropTestDatabase(): void
    {
        self::$pdo->exec(sprintf(
            'DROP DATABASE %s;',
            static::$mysqlConnection['database'],
        ));
    }
}

MySqlTestCase::init();
