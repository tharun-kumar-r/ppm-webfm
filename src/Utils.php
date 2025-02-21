<?php
class Utils {
    
    // Sanitize input data
    public static function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // Hash password securely
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    // Verify password against stored hash
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    // Parse a date into a formatted string
    public static function parseDate($data) {
        try {
            $dateTime = new DateTime($data);
            return ["msg" => $dateTime->format('jS F Y h:i A'), "sts" => true];
        } catch (Exception $e) {
            return ["msg" => MESSAGES['INVALID_DATE'], "sts" => false];
        }
    }

    // Get current date and time in different formats
    public static function dateTimeNow($type = 'full') {
        $formats = [
            'full'     => 'jS F Y h:i A',
            'date'     => 'Y-m-d',
            'time'     => 'H:i:s',
            'time12'   => 'h:i A',
            'datetime' => 'Y-m-d H:i:s',
            'day'      => 'l',
            'month'    => 'F',
            'year'     => 'Y',
            'short'    => 'd M Y',
            'iso'      => 'c'
        ];

        return ["msg" => date($formats[$type] ?? $formats['full']), "sts" => true];
    }

    // Get user's real IP address
    public static function myIp() {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ipList[0]); // Get first IP in forwarded list
        }
        return ["msg" => $ip, "sts" => true];
    }

    // Generate SEO-friendly URL slugs
    public static function urlSlug($string) {
        if (empty($string)) {
            return ["msg" => MESSAGES['INVALID_INPUT'], "sts" => false];
        }

        $string = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
        return ["msg" => strtolower(trim($string, '-')), "sts" => true];
    }

    // Parce Url to Text
    public static function getUrlText($url, $true = true) {
        $path = parse_url($url, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        $lastSegment = end($segments);
        $title = ucwords($true ? str_replace('-', ' ', $lastSegment) : $lastSegment);
        return ["msg" => self::sanitize($title), "sts" => true];
    }

    // Get Current Url as Text
    public static function getCurrentUrl($current = true) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];        
        return $protocol . "://" . $host . ($current ? $uri : "");
    }

}

?>
