<?php
namespace Core;

use PDO;

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (!self::$instance) {
            $config = require __DIR__ . '/../config/database.php';

            self::$instance = new PDO(
                $config['dsn'],
                $config['user'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        }

        return self::$instance;
    }
}
