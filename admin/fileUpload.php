<?php
require_once "fileManagerconfig.php";

if (!is_dir($uploads_dir))
    mkdir($uploads_dir, 0777, true);

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $path = isset($_POST['path']) ? $uploads_dir . trim($_POST['path'], '/') . '/' : $uploads_dir;

    function deleteFolder($folder)
    {
        if (!is_dir($folder))
            return false;
        $files = array_diff(scandir($folder), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $folder . DIRECTORY_SEPARATOR . $file;
            is_dir($filePath) ? deleteFolder($filePath) : unlink($filePath);
        }
        return rmdir($folder);
    }

    function formatSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }

    function getFolderSize($folder)
    {
        $size = 0;
        foreach (glob($folder . '/*', GLOB_NOSORT) as $file) {
            $size += is_file($file) ? filesize($file) : getFolderSize($file);
        }
        return $size;
    }

    if ($action == "list") {
        $files = array_diff(scandir($path), ['.', '..']);
        $data = [];

        foreach ($files as $file) {
            $filePath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file; // Remove extra slashes

            $data[] = [
                'name' => $file,
                'is_dir' => is_dir($filePath),
                'size' => is_dir($filePath) ? '-' : formatSize(filesize($filePath)),
                'path' => str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath($filePath)), // Ensure proper relative path
                'upload_date' => date("Y-m-d H:i:s", filemtime($filePath)) // Include file upload date
            ];
        }

        // Sort files and folders by upload_date (newest first)
        usort($data, function ($a, $b) {
            return strtotime($b['upload_date']) - strtotime($a['upload_date']);
        });

        echo json_encode($data);
    }

    function convertToWebP($source, $destination) {
        $info = getimagesize($source);
        if ($info === false) {
            return false; 
        }    
        $mime = strtolower($info['mime']); 
    
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg': 
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                imagepalettetotruecolor($image);
                imagealphablending($image, false);
                imagesavealpha($image, true);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($source);
                break;
            case 'image/bmp':
                $image = imagecreatefrombmp($source);
                break;
            default:
                return false; 
        }
        $result = imagewebp($image, $destination, 80);     
        imagedestroy($image);
        return $result;
    }
    
    if ($action == "upload" && isset($_FILES['files'])) {
        if (getFolderSize($uploads_dir) <= $allowedsize) {
            foreach ($_FILES['files']['name'] as $key => $fileName) {
                $fileTmpName = $_FILES['files']['tmp_name'][$key];
                $fileSize = $_FILES['files']['size'][$key];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
                // Validate file extension and size
                if (!in_array($fileExtension, $allowedExtensions) || $fileSize > $maxFileSize || !is_uploaded_file($fileTmpName)) {
                    echo "invalid";
                    continue;
                }
    
                // Sanitize file name (Remove special characters, replace spaces, add random number)
                $sanitizedName = preg_replace('/[^a-zA-Z0-9\s.-]/', '', pathinfo($fileName, PATHINFO_FILENAME));
                $sanitizedName = str_replace(' ', '-', $sanitizedName);
                $uniqueFileName = $sanitizedName . '-' . rand(1000, 9999);
    
                // Check if convert_webp flag is enabled
                if ($_POST['convert_webp'] == 1 && in_array($fileExtension, ['jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'gif', 'bmp'])) {
                    $destinationFile = $path . $uniqueFileName . '.webp';
                    if (convertToWebP($fileTmpName, $destinationFile)) {
                        echo "success";
                    } else {
                        echo "error";
                    }
                } else {
                    // Upload non-image files or if WebP conversion is disabled
                    $destinationFile = $path . $uniqueFileName . '.' . $fileExtension;
                    if (move_uploaded_file($fileTmpName, $destinationFile)) {
                        echo "success";
                    } else {
                        echo "error";
                    }
                }
            }
        } else {
            echo "error";
        }
    }    

    if ($action == "create_folder" && !empty($_POST['folder_name'])) {
        echo mkdir($path . $_POST['folder_name'], 0777) ? "success" : "error";
    }

    if ($action == "delete" && !empty($_POST['filename'])) {
        $fileToDelete = $path . $_POST['filename'];
        if (is_dir($fileToDelete)) {
            echo deleteFolder($fileToDelete) ? "success" : "error";
        } else {
            echo unlink($fileToDelete) ? "success" : "error";
        }
    }

    if ($action == "get_size") {
        echo json_encode(['size' => getFolderSize($uploads_dir)]);
    }
}
