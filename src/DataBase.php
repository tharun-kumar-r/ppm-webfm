<?php

require_once 'Config.php';

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $dbConfig = Config::DB; 
            $this->pdo = new PDO(
                "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}", 
                $dbConfig['username'], 
                $dbConfig['password']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage()); 
        }
    }

    public static function sql() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}
