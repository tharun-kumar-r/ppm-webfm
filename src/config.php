<?php 
require_once 'Constants.php';

class Config {
    public const APP = [
        'name' => 'MyApp',
        'version' => '1.0.0',
        'dev' => true, //For Indexing
        'isDynamicApp' => true, //Dynamic App
    ];

    public const DB = [
        'host' => 'localhost',
        'dbname' => 'test',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'initDB'=> true, //For app Database    
    ];

    public const SESSION_TYPE = [
        'type' => STRINGS['SESSION'], //STRINGS['COOKIE']
        'session_name' => 'TEST_DEV_SNAME',
        'secret' => 'TEST_DEV'
    ];
}

?>
