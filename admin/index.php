<?php

require_once '../src/Config.php';
require_once '../src/packages/Router.php';
Config::APP['isDynamicApp'] && (require_once '../src/DBFunctions.php') && define('CORE', DBFunctions::pdo());
use Steampixel\Route;
define('BASEPATH', Config::APP['adminPath']);

Route::add('/', function () {
   require "views/login.php";
}, ['GET', 'POST']);

Route::add('/home', function () {
    require "views/home.php";
}, ['GET', 'POST']);


Route::add('/file-manager', function () {
    require "fileManager.php";
});

/*
//SingleParm
Route::add('/user/([0-9]*)', function ($id) {
}, 'GET', 'POST');

//MultiParm
Route::add('/tours/([0-9]+)/(.+)', function ($id, $name) {
}, 'GET', 'POST');

//API POST
Route::add('/api/login', function() {
    $data = json_decode(file_get_contents("php://input"), true);
    echo json_encode(Auth::login($data['email'], $data['password']));
}, 'POST');

//API GET
Route::add('/api/logout', function() {
    echo json_encode(Auth::logout());
}, 'GET');
*/

Route::pathNotFound(function ($path) {
    require "../views/notFound.php";
});

Route::run(BASEPATH);




?>