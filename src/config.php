<?php 
require_once 'constants.php';

class Config {
    public const GENERAL = [
        'name' => 'MyApp',
        'version' => '1.0.0',
        'debug' => true
    ];

    public const DB = [
        'host' => 'localhost',
        'dbname' => 'my_database',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ];

    public const SESSION_TYPE = [
        'type' => STRINGS.SESSION, //STRINGS.COOKIE
        'session_name' => 'session_uid',
        'secret' => ''
    ];
}


class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $dbConfig = Config::DB;  
            $this->pdo = new PDO(
                "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}", $dbConfig['username'], $dbConfig['password']
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

?>
