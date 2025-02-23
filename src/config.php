<?php 
require 'Constants.php';
date_default_timezone_set('Asia/Kolkata');

class Config {
    public const APP = [
        'name' => 'MyApp',
        'version' => '1.0.0',
        'dev' => true, //For Indexing
        'isDynamicApp' => true, //Dynamic App
        'businessType' => 'Software Company',
        'logo' => 'https://rajsoft.org.in/img/icon.png',
        'country' => 'IN',
        //For Static website
        'metaData' => [
            'meta_description' => 'Test Description',
            'meta_keywords' => 'Test Keyword',
            'og_image' => 'https://rajsoft.org.in/img/icon.png',
        ],
        'uploads_dir' => "uploads/",
        'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'doc', 'docx'],
        'maxFileSize' => (6 * 1024 * 1024),
        'allowedsize' => (6 * 1024 * 1024)

    ];

    public const DB = [
        'host' => 'localhost',
        'dbname' => 'test',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'initDB'=> false, //For app Database    
    ];

    public const SESSION_TYPE = [
        'type' => STRINGS['COOKIE'], //STRINGS['SESSION'], 
        'session_name' => 'TEST_DEV_SNAME',
        'secret' => 'TEST_DEV'
    ];
}

?>
