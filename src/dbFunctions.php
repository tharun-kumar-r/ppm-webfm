<?php
require_once 'utils.php';
require_once 'dataBase.php';

class DBFunctions
{
    private static $cipher = "AES-256-CBC";
    private static $key;
    private static $session;
    private static $iv = "1f2e3d4c5b6a7980"; // Must be 16 bytes
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $this->pdo = Database::sql();

        if (isset(Config::SESSION_TYPE['secret'])) {
            self::$key = Config::SESSION_TYPE['secret']; // Ensure secret key exists
        } else {
            throw new Exception("Encryption key is not set in config.");
        }
        self::$session = Config::SESSION_TYPE ?? null;
    }

    // Get a single instance of DBFunctions
    public static function pdo()
    {
        if (self::$instance === null) {
            self::$instance = new DBFunctions();
        }
        return self::$instance;
    }

    public function encrypt($plaintext)
    {
        if (!isset(self::$key)) {
            throw new Exception("Encryption key is not set.");
        }
        return base64_encode(openssl_encrypt(json_encode($plaintext), self::$cipher, self::$key, 0, self::$iv));
    }

    public function decrypt($encryptedText)
    {
        if (!isset(self::$key)) {
            throw new Exception("Decryption key is not set.");
        }
        return json_decode(openssl_decrypt(base64_decode($encryptedText), self::$cipher, self::$key, 0, self::$iv), true);
    }

    // Universal Query Shortcut
    public function query($sql, $params = [], $fetchOne = false)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $fetchOne ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Execute Queries with Transaction Support
    public function execute($sql, $params = [], $noSession = false)
    {
        if (!$noSession) {
            $checksession = self::checkSession();
            if ($checksession['sessionSts'] == false) {
                return ["msg" => MSG['SE'], "sts" => false];
            }
        }

        try {
            $pdo = $this->pdo;
            $pdo->beginTransaction(); // Start transaction
            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute($params);

            if ($success) {
                $pdo->commit(); // Commit if successful
                return ["msg" => MSG['ES'], "sts" => true];
            } else {
                $pdo->rollBack(); // Rollback if failed
                return ["msg" => MSG['EF'], "sts" => false];
            }
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return ["msg" => $e->getMessage(), "sts" => false];
        }
    }

    // Execute Batch Queries
    public function executeBatch($queries, $noSession = false)
    {
        if (!$noSession) {
            $checksession = self::checkSession();
            if ($checksession['sessionSts'] == false) {
                return ["msg" => MSG['SE'], "sts" => false];
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
            return ["msg" => MSG['ES'], "sts" => true];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return ["msg" => $e->getMessage(), "sts" => false];
        }
    }

    // Login User
    public function login($email, $password, $saveLogin = false)
    {
        $user = self::query("SELECT * FROM users WHERE email = ? LIMIT 1", [$email], true);
        $expiry_time = time() + ($saveLogin ? (30 * 24 * 60 * 60) : (3 * 60 * 60));
        if ($user && Utils::verifyPassword($password, $user['password'])) {
            $authKey = self::encrypt([
                'uid' => $user['uid'],
                'email' => $user['email'],
                'name' => $user['name'],
                'profile' => $user['profile'],
                'type' => $user['type'],
                'generatedOn' => time()
            ]);

            // Insert the new token
            self::execute("INSERT INTO token (uid, token_id, valid_till) VALUES (?, ?, ?)", [
                $user["uid"],
                $authKey,
                $expiry_time
            ], true);

            if (self::$session['type'] === STR['COOKIE']) {
                setcookie(self::$session['sessionName'], $authKey, [
                    "httponly" => true,
                    "samesite" => "Strict",
                    "path" => "/",
                    "expires" => $expiry_time
                ]);
            } else {
                $_SESSION[self::$session['sessionName']] = $authKey;
            }
            return ["msg" => 'Login' . MSG['SL'], "sts" => true];
        }
        return ["msg" => 'Login' . MSG['F'], "sts" => false];
    }

    public function checkSession()
    {
        if (self::$session['type'] === STR['COOKIE']) {
            $token = $_COOKIE[self::$session['sessionName']] ?? null;

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
            return !empty($_SESSION[self::$session['sessionName']])
                ? ['sessionSts' => true]
                : self::logout();
        }
    }


    public function logout()
    {
        if (self::$session['type'] === STR['COOKIE']) {
            setcookie(self::$session['sessionName'], "", time() - 3600, "/", "", true, true);
        } else {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_destroy();
        }
        return ["msg" => 'Logout' . MSG['SY'], "sts" => true, 'sessionSts' => false];
    }

    public function userLoggedIn()
    {
        if (self::$session['type'] === STR['COOKIE']) {
            return self::decrypt($_COOKIE[self::$session['sessionName']]);
        } else {
            return self::decrypt($_SESSION[self::$session['sessionName']]);
        }
    }

    public function checkBot()
    {
        $captchaResponse = $_POST["cf-turnstile-response"] ?? '';
        $verifyUrl = "https://challenges.cloudflare.com/turnstile/v0/siteverify";
        $data = [
            'secret' => Config::SESSION_TYPE['recapcha'],
            'response' => $captchaResponse
        ];
        $options = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($verifyUrl, false, $context);
        $responseData = json_decode($result);

        if (!$responseData->success) {
            return ["msg" => 'Recaptcha verification failed!. Please try again.', "sts" => false];
        } else {
            return ["msg" => 'Recaptcha verification successful!.', "sts" => true];
        }
    }
}

