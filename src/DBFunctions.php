<?php
require_once 'config.php';

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

    public static function encrypt($plaintext) {
        if (!isset(self::$key)) {
            throw new Exception("Encryption key is not set.");
        }
        return base64_encode(openssl_encrypt($plaintext, self::$cipher, self::$key, 0, self::$iv));
    }

    public static function decrypt($encryptedText) {
        if (!isset(self::$key)) {
            throw new Exception("Decryption key is not set.");
        }
        return openssl_decrypt(base64_decode($encryptedText), self::$cipher, self::$key, 0, self::$iv);
    }
    
    // Universal Query Shortcut
    public function query($sql, $params = [], $fetchOne = false) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $fetchOne ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Execute INSERT, UPDATE, DELETE Queries with Transaction Support
    public function execute($sql, $params = []) {
        $checksession = checkSession();
        if($checksession['sts'] == 0){
            return ["msg" => MESSAGES.SESSION_EXPIRED, "sts" => 1];
        }
        try {
            $this->pdo->beginTransaction(); // Start transaction
            $stmt = $this->pdo->prepare($sql);
            $success = $stmt->execute($params);

            if ($success) {
                $this->pdo->commit(); // Commit if successful
                return true;
            } else {
                $this->pdo->rollBack(); // Rollback if failed
                return false;
            }
        } catch (PDOException $e) {
            $this->pdo->rollBack(); // Rollback on exception
            error_log("DB Error: " . $e->getMessage()); // Log error
            return false;
        }
    }

    public function executeBatch($queries) {
        try {
            $this->pdo->beginTransaction(); // Start Transaction
    
            foreach ($queries as $query) {
                if (!isset($query['sql'])) {
                    throw new Exception("Query statement missing in batch execution.");
                }
                $stmt = $this->pdo->prepare($query['sql']);
                $stmt->execute($query['params'] ?? []);
            }
    
            $this->pdo->commit(); // Commit all queries
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack(); // Rollback if any query fails
            error_log("Batch Query Error: " . $e->getMessage());
            return "Error: " . $e->getMessage();
        }
    }
    
    // Login User
    public static function login($email, $password, $saveLogin = false) {
        $user = self::query("SELECT * FROM users WHERE email = ?", [$email], true);
        if ($user && password_verify($password, $user['password'])) {
            $authKey = self::encrypt([
                'id' => $user['id'], 
                'uid' => $user['uid'], 
                'email' => $user['email'], 
                'name' => $user['name'], 
                'profile' => $user['profile']
            ]);
    
            $expiry_time = time() + ($saveLogin ? (30 * 24 * 60 * 60) : (1 * 60 * 60));
            // Insert the new token
            self::execute("INSERT INTO t_token (uid, tokenId, validTill) VALUES (?, ?, ?)", [
                $user["uid"], $authKey, $expiry_time
            ]);
    
            if (self::$session['type'] === STRINGS.COOKIE) {
                // Store Auth Key in Secure HTTP-only Cookie
                setcookie(self::$session['session_name'], $authKey, [
                    "httponly" => true,  
                    //"secure" => true,    
                    "samesite" => "Strict", 
                    "path" => "/",       
                    "expires" => time() + ($saveLogin ? (30 * 24 * 60 * 60) : 3600) // Fixed expiration
                ]);
    
                return ["msg" => MESSAGES.L_SUCCESS, "sts" => 1];
            } else {
                $_SESSION[self::$session['session_name']] = $authKey;
                return ["msg" => MESSAGES.L_SUCCESS, "sts" => 1];
            }
        }
        return ["msg" => MESSAGES.L_FAILED, "sts" => 0];
    }
    
    public static function checkSession()
    {
        static $token;
        if (self::$session['type'] === STRINGS.COOKIE) {
            $token = $_COOKIE[self::$session['session_name']];
        } else {
            $token = $_SESSION[self::$session['session_name']];
        }

        $user = self::query("SELECT count(id) FROM t_token WHERE tokenId = ?", [$token], true);
        return empty($user) ? logout() : ['sts'=>0];
    }

    public static function logout() {       
        if (self::$session['type'] === STRINGS.COOKIE) {
            setcookie(self::$session['session_name'], "", time() - 3600, "/", "", true, true); // Expire the cookie
        } else {
            session_destroy();
        }
        return ["msg" => MESSAGES.L_LOGOUT_S, "sts" => 1];
    }




}
?>
