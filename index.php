<?php
require_once 'src/packages/router.php';
require_once 'src/auth.php';

use Steampixel\Router;

Router::add('/', function() { require 'views/home.php'; });
Router::add('/contact', function() { require 'views/contact.php'; });

Router::add('/api/login', function() {
    $data = json_decode(file_get_contents("php://input"), true);
    echo json_encode(Auth::login($data['email'], $data['password']));
}, 'POST');

Router::add('/api/logout', function() {
    echo json_encode(Auth::logout());
}, 'GET');

Router::pathNotFound(function() { echo "404 - Page Not Found"; });
Router::run('/');
?>
