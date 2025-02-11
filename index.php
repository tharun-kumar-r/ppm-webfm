<?php
require_once 'src/Config.php';
require_once 'src/packages/Router.php';
Config::APP['isDynamicApp'] && (require_once 'src/DBFunctions.php') && DBFunctions::pdo();
use Steampixel\Route;
define('BASEPATH', '/');

Route::add('/', function () {
    $title = "";
    require "views/home.php";
});

Route::add('/contact', function () {
    $title = "";
    require "src/contact.php";
}, 'GET', 'POST');




/*
//SingleParm
Route::add('/user/([0-9]*)', function ($id) {
    $title = "";
}, 'GET', 'POST');

//MultiParm
Route::add('/tours/([0-9]+)/(.+)', function ($id, $name) {
     $title = "";
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
    $title = "File not found!.";
    require "src/notfound.php";
});



Route::run('/');
?>

