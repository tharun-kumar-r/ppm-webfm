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
            if (Config::SESSION_TYPE['type'] === STR['SESSION']) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }}
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
if (Config::DB['initDB']) {
    try {
        $pdo = Database::sql();
        $sqlFile = __DIR__ . '/import.sql';
        if (file_exists($sqlFile)) {
            $sqlContent = file_get_contents($sqlFile);
            $pdo->beginTransaction();
            $pdo->exec($sqlContent);
            $pdo->commit();
            echo "Database initialized successfully.";
        } else {
            die("SQL file not found.");
        }
    } catch (PDOException $e) {
        die("<font style='font-size:19px;color:red'>Error importing SQL file: " . $e->getMessage() . " Plase turn off AppInt</font>");
    }
}
