<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('HOUSEGYM_DB_HOST') ?: 'localhost';
    $dbname = getenv('HOUSEGYM_DB_NAME') ?: 'housegym';
    $user = getenv('HOUSEGYM_DB_USER') ?: 'root';
    $pass = getenv('HOUSEGYM_DB_PASS') ?: '';

    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);

    return $pdo;
}