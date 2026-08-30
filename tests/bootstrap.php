<?php declare(strict_types = 1);

use Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

$dotenv = Dotenv::createUnsafeMutable(__DIR__);
$dotenv->load();
