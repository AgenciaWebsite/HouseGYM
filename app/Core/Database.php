<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * Clase Database
 * 
 * Implementa el patrón Singleton para la conexión a la base de datos PDO.
 * Garantiza que solo exista una conexión activa durante la petición.
 */
class Database
{
    private static ?PDO $pdo = null;

    /**
     * Obtiene la instancia de la conexión a la base de datos.
     *
     * @return PDO La conexión PDO.
     * @throws PDOException Si falla la conexión.
     */
    public static function getConnection(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
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

        self::$pdo = new PDO($dsn, $user, $pass, $options);

        return self::$pdo;
    }
}
