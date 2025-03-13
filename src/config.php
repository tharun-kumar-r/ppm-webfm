<?php 
require 'constants.php';
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
        'uploads_dir' => "../uploads/",
        'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'doc', 'docx'],
        'maxFileSize' => (6 * 1024 * 1024),
        'allowedsize' => (6 * 1024 * 1024),
        'adminPath' => '/admin/'
    ];

    public const DB = [
        'host' => 'localhost', 
        'dbname' => 'u849653985_test',
        'username' => 'u849653985_test',
        'password' => '+aS6d5/E3',
        'charset' => 'utf8mb4',
        'initDB'=> false, //For app Database    
    ];

    public const SESSION_TYPE = [
        'type' => STR['COOKIE'], //STRINGS['SESSION'], 
        'sessionName' => 'TEST_DEV_SNAME',
        'secret' => 'TEST_DEV',
        'recapcha' => '1x0000000000000000000000000000000AA'
    ];

    public const IMPORT = [
        'header' => '
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0/dist/css/tabler.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
        ',
        'footer' => '
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0/dist/js/tabler.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/just-validate@latest/dist/just-validate.production.min.js"></script>
        <script src="/assets/common/script.js"></script>
        ',
        'popupjs' =>'<script src="https://cdn.jsdelivr.net/gh/tharun-kumar-r/PopupJs/PopupJs.min.js?v=1.0"></script>',
        'cloudflare' => '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>',
        'appJs' => '<script src="/assets/js/app.js"></script>',
        'appAdminJs' => '<script src="' . self::APP['adminPath'] . 'assets/js/app.js"></script>'
        
    ];

}

?>
