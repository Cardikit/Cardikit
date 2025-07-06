<?php

namespace App\Core;

use PDO;
use PDOException;
use App\Core\Config;

/**
* Provides a singleton-style PDO connection using environment config variables.
*
* @package App\Core
*
* @since 0.0.1
*/
class Database
{
    /**
    * The shared PDO connection instance.
    *
    * @var PDO|null
    *
    * @since 0.0.1
    */
    protected static ?PDO $connection = null;

    /**
    * Establishes and returns the PDO connection.
    * If already connected, returns the existing instance.
    *
    * @return PDO
    *
    * @since 0.0.1
    */
    public static function connect(): PDO
    {
        if (self::$connection === null) {
            // Load database credentials from config
            $host = Config::get('MYSQL_HOST', '127.0.0.1');
            $name = Config::get('MYSQL_DATABASE', 'cardikit');
            $user = Config::get('MYSQL_USER', 'root');
            $pass = Config::get('MYSQL_PASSWORD', '');

            $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4";

            try {
                self::$connection = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            } catch (PDOException $e) {
                die("DB Connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
