<?php

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {

            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                DATABASE_HOST,
                DATABASE_NAME,
                DATABASE_CHARSET
            );

            self::$instance = new PDO(
                $dsn,
                DATABASE_USER,
                DATABASE_PASSWORD,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        }

        return self::$instance;
    }
}
