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
            return ["msg" => MESSAGES::INVALID_DATE, "sts" => false];
        }
    }

    // Get current date and time in different formats
    public static function dateTimeNow($type = 'full') {
        date_default_timezone_set('UTC'); // Ensure consistent timezone

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

    // Secure file upload
    public static function uploadFile($file, $uploadDir, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf']) {
        if (!isset($file['name']) || !isset($file['tmp_name'])) {
            return ["msg" => MESSAGES::INVALID_FILE, "sts" => false];
        }

        $fileExt = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        if (!in_array($fileExt, $allowedTypes)) {
            return ["msg" => MESSAGES::FILE_TYPE_NOT_ALLOWED, "sts" => false];
        }

        $uniqueName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $fileExt;
        $targetFile = $uploadDir . $uniqueName;

        return move_uploaded_file($file["tmp_name"], $targetFile) 
            ? ["msg" => $targetFile, "sts" => true] 
            : ["msg" => MESSAGES::UPLOAD_FAILED, "sts" => false];
    }

    // Secure file download
    public static function downloadFile($filePath, $fileName) {
        if (!file_exists($filePath)) {
            return ["msg" => MESSAGES::FILE_NOT_FOUND, "sts" => false];
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    // Generate SEO-friendly URL slugs
    public static function urlSlug($string) {
        if (empty($string)) {
            return ["msg" => MESSAGES::INVALID_INPUT, "sts" => false];
        }

        $string = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
        return ["msg" => strtolower(trim($string, '-')), "sts" => true];
    }

    // Send a JSON response
    public static function jsonResponse($data, $status = 200) {
        header("Content-Type: application/json");
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    // API request using cURL
    public static function apiRequest($url, $method = 'GET', $data = [], $headers = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $headers[] = 'Content-Type: application/json';
        }

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return ["msg" => $error, "sts" => false];
        }

        curl_close($ch);
        return ["msg" => json_decode($response, true), "sts" => true];
    }
}

?>
