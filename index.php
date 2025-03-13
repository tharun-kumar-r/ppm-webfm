<?php
ob_start(); // Start output buffering

require_once 'src/config.php';
require_once 'src/packages/router.php';
Config::APP['isDynamicApp'] && (require_once 'src/dbFunctions.php') && define('CORE', DBFunctions::pdo());
use Steampixel\Route;
define('BASEPATH', '/');

Route::add('/', function () use ($pdo){
    require "views/home.php";
    //echo CORE->encrypt("sacssdddsfsdsac");
    //print_r(CORE->login("tharun@gmail.com", 'tharun@gmail.com'));
    print_r(CORE->userLoggedIn());
});

Route::add('/contact-us', function () {
    require "views/home.php";
}, ['GET', 'POST']);



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
    require "views/notFound.php";
});

Route::run(BASEPATH);


$content = ob_get_contents(); // Get buffered content
ob_end_clean(); // End buffering and clean output
$pageSize = strlen($content); // Calculate size in bytes
// Display the page size before sending the content
echo "Page Output Size: {$pageSize} bytes<br>";
// Send the buffered content to the browser
echo $content;
?>