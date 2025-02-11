<?php 
require_once 'Constants.php';

class Config {
    public const APP = [
        'name' => 'MyApp',
        'version' => '1.0.0',
        'dev' => true,
        'isDynamicApp' => true,
        'isIndexAble' => false,   
    ];

    public const DB = [
        'host' => 'localhost',
        'dbname' => 'test',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ];

    public const SESSION_TYPE = [
        'type' => STRINGS['SESSION'], //STRINGS['COOKIE']
        'session_name' => 'session_uid',
        'secret' => 'grfgrsdgdfdfbfd'
    ];
}

?>
