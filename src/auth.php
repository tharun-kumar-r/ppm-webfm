<?php
require_once 'dbFunctions.php';
session_start();

class Auth {
    private static $secret;

    public static function init() {
        self::$secret = Config::SESSION_TYPE;
    }



    // Logout User (Expire Cookie)
    public static function logout() {
        setcookie("auth_key", "", time() - 3600, "/", "", true, true); // Expire the cookie
        session_destroy();
        return ["msg" => MESSAGES.L_LOGOUT_S];
    }

    // Validate Authentication Key
    public static function validateAuthKey($authKey) {
        $secret = self::$secret['secret'];
        $decrypted = openssl_decrypt(base64_decode($authKey), 'AES-256-CBC', $secret, 0, substr($secret, 0, 16));

        if (!$decrypted) return false;

        $data = json_decode($decrypted, true);
        return isset($data['exp']) && $data['exp'] > time();
    }

    // Secure API Access
    public static function isAuthenticated() {
        if (self::$secret['type'] === STRINGS.SESSION) {
            return isset($_SESSION['user_id']);
        } else {
            // Get authentication key from cookie
            if (!isset($_COOKIE['auth_key'])) return false;
            return self::validateAuthKey($_COOKIE['auth_key']);
        }
    }
}


?>
