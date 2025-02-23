<?php
require_once 'src/Config.php';
require_once 'src/packages/Router.php';
Config::APP['isDynamicApp'] && (require_once 'src/DBFunctions.php') && $db = DBFunctions::pdo();
use Steampixel\Route;
define('BASEPATH', '/');

Route::add('/', function () {
    require "views/home.php";  

        // echo Utils::hashPassword("tharun@gmail.com") . "  |   "; 
    

   //print_r($db->login("tharun@gmail.com", 'tharun@gmail.com'));

    print_r($db->encrypt("UTRrdHZwd1dQa2JjaW4rWWRFWEFPeVk2Z0xVWGhFK1ExbG9IakE3cmFqSFJCdnNHWXZzTkk5L1dzajJnV2xzNzl1eCs5ZmJMRWhFOFhJWWVqcGYvWlRpTU9SRnNlRmx2RS91UlRkTTNlY0VJTE1FUXQ1TVZNSnFkZVNjMmJKbEdVK0s3Y2JxOGdzNG5JMFlyVGtjdERvaGFnNDBvYU5ydmxlNnk4WlpIc25ZPQ"));
    //echo(DBFunctions::checkSession()['sessionSts'] ? "Session yes" : "Session no" );
    //print_R(DBFunctions::getSessionValue());

   //print_r($db->userLoggedIn());
    

});

Route::add('/contact-us', function () {
    require "views/home.php";
}, ['GET', 'POST']);

Route::add('/fileManager', function () {
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
    require "views/notFound.php";
});

Route::run(BASEPATH);
?>