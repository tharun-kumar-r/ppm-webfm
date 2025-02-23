<?php
require_once 'Utils.php';
require_once 'DataBase.php';

class DBFunctions {
    private static $cipher = "AES-256-CBC"; 
    private static $key;
    private static $session;
    private static $iv = "1f2e3d4c5b6a7980"; // Must be 16 bytes
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $this->pdo = Database::sql();
        
        if (isset(Config::SESSION_TYPE['secret'])) {
            self::$key = Config::SESSION_TYPE['secret']; // Ensure secret key exists
        } else {
            throw new Exception("Encryption key is not set in config.");
        }
        self::$session = Config::SESSION_TYPE ?? null;
    }
    
    // Get a single instance of DBFunctions
    public static function pdo() {
        if (self::$instance === null) {
            self::$instance = new DBFunctions();
        }
        return self::$instance;
    }

    public function encrypt($plaintext) {
        if (!isset(self::$key)) {
            throw new Exception("Encryption key is not set.");
        }
        return base64_encode(openssl_encrypt(json_encode($plaintext), self::$cipher, self::$key, 0, self::$iv));
    }

    public function decrypt($encryptedText) {
        if (!isset(self::$key)) {
            throw new Exception("Decryption key is not set.");
        }
        return json_decode(openssl_decrypt(base64_decode($encryptedText), self::$cipher, self::$key, 0, self::$iv), true);
    }
    
    // Universal Query Shortcut
    public function query($sql, $params = [], $fetchOne = false) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $fetchOne ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Execute Queries with Transaction Support
    public function execute($sql, $params = [], $noSession = false) {
        if (!$noSession) {
            $checksession = self::checkSession();
            if ($checksession['sessionSts'] == false) {
                return ["msg" => MESSAGES['SESSION_EXPIRED'], "sts" => false];
            }
        }
    
        try {
            $pdo = $this->pdo;
            $pdo->beginTransaction(); // Start transaction
            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute($params);
    
            if ($success) {
                $pdo->commit(); // Commit if successful
                return ["msg" => MESSAGES['EXEC_SUCCESS'], "sts" => true]; 
            } else {
                $pdo->rollBack(); // Rollback if failed
                return ["msg" => MESSAGES['EXEC_FAILED'], "sts" => false]; 
            }
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return ["msg" => $e->getMessage(), "sts" => false]; 
        }
    }

    // Execute Batch Queries
    public function executeBatch($queries, $noSession = false) {
        if (!$noSession) {
            $checksession = self::checkSession();
            if ($checksession['sessionSts'] == false) {
                return ["msg" => MESSAGES['SESSION_EXPIRED'], "sts" => false];
            }
        }

        try {
            $pdo = $this->pdo;
            $pdo->beginTransaction(); // Start Transaction

            foreach ($queries as $query) {
                if (!isset($query['sql'])) {
                    throw new Exception("Query statement missing in batch execution.");
                }
                $stmt = $pdo->prepare($query['sql']);
                $stmt->execute($query['params'] ?? []);
            }

            $pdo->commit(); // Commit all queries
            return ["msg" => MESSAGES['EXEC_SUCCESS'], "sts" => true]; 
        } catch (PDOException $e) {
            $this->pdo->rollBack(); 
            return ["msg" => $e->getMessage(), "sts" => false];
        }
    }
    
    // Login User
    public function login($email, $password, $saveLogin = false) {
        $user = self::query("SELECT * FROM users WHERE email = ?", [$email], true);
        $expiry_time = time() + ($saveLogin ? (30 * 24 * 60 * 60) : (3 * 60 * 60));
        if ($user && Utils::verifyPassword($password, $user['password'])) {
            $authKey = self::encrypt([
                'uid' => $user['uid'], 
                'email' => $user['email'], 
                'name' => $user['name'], 
                'profile' => $user['profile'],
                'type' => $user['type']
            ]);
                       
           // Insert the new token
           self::execute("INSERT INTO token (uid, token_id, valid_till) VALUES (?, ?, ?)", [
                $user["uid"], $authKey, $expiry_time
            ], true);

            if (self::$session['type'] === STRINGS['COOKIE']) {
                setcookie(self::$session['session_name'], $authKey, [
                    "httponly" => true,  
                    "samesite" => "Strict", 
                    "path" => "/",       
                    "expires" => $expiry_time 
                ]);
            } else {
                $_SESSION[self::$session['session_name']] = $authKey;
            }
            return ["msg" => MESSAGES['L_SUCCESS'], "sts" => true];
        }
        return ["msg" => MESSAGES['L_FAILED'], "sts" => false];
    }
    
    public function checkSession() {
        if (self::$session['type'] === STRINGS['COOKIE']) {
            $token = $_COOKIE[self::$session['session_name']] ?? null;
            
            if (!$token) {
                return self::logout();
            }
    
            $user = self::query(
                "SELECT COUNT(id) as count FROM token WHERE token_id = ? AND valid_till > UNIX_TIMESTAMP()", 
                [$token], 
                true
            );
    
            return ($user && $user['count'] > 0) ? ['sessionSts' => true] : self::logout();
        } else {
            return !empty($_SESSION[self::$session['session_name']]) 
                ? ['sessionSts' => true] 
                : self::logout();
        }
    }
    

    public function logout() {       
        if (self::$session['type'] === STRINGS['COOKIE']) {
            setcookie(self::$session['session_name'], "", time() - 3600, "/", "", true, true);
        } else {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_destroy();
        }
        return ["msg" => MESSAGES['L_LOGOUT_S'], "sts" => true, 'sessionSts' => false];
    }

    public function userLoggedIn() {
        if (self::$session['type'] === STRINGS['COOKIE']) {
            return self::decrypt($_COOKIE[self::$session['session_name']]);
        } else {
            return self::decrypt($_SESSION[self::$session['session_name']]);
        }
    }
}

?>
