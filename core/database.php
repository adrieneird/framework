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
                $GLOBALS['database_host'],
                $GLOBALS['database_name'],
                $GLOBALS['database_charset']
            );

            self::$instance = new PDO(
                $dsn,
                $GLOBALS['database_user'],
                $GLOBALS['database_password'],
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
