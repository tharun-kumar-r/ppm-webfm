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
if (Config::DB['initDB']) {
    try {
        $pdo = Database::sql();
        $sql = "CREATE TABLE IF NOT EXISTS t_users (
            id           INT AUTO_INCREMENT PRIMARY KEY,
            uid          VARCHAR(20) UNIQUE NOT NULL, 
            u_name       VARCHAR(100) NOT NULL,
            u_email      VARCHAR(150) UNIQUE NOT NULL,
            u_password   VARCHAR(255) NOT NULL,
            u_profile    VARCHAR(255) DEFAULT NULL,
            u_type       ENUM('admin', 'user', 'worker') DEFAULT 'user',
            created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $pdo->exec($sql);
        $sql = "CREATE TABLE IF NOT EXISTS token (
            id           INT AUTO_INCREMENT PRIMARY KEY,
            uid          VARCHAR(20) NOT NULL, 
            token_id     VARCHAR(255) UNIQUE NOT NULL,
            valid_till   TIMESTAMP NOT NULL,
            created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (uid) REFERENCES t_users(uid) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $pdo->exec($sql);
        $file = "Config.php";
        $content = file_get_contents($file);  
        if(!$content){
            echo "<font color='red'>Error: Unable to read file. Please set AppInit <b>False</b></font><br><Br>";
        }else{
            $updatedContent = preg_replace("/'initApp'\s*=>\s*true/", "'initApp' => false", $content);
            file_put_contents($file, $updatedContent);
        }
    } catch (PDOException $e) {
        die("Error creating tables: " . $e->getMessage());
    }
}
