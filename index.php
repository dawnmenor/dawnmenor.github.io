<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$password = "password";
$user = 'user';

$is_authorized = ($_SERVER['PHP_AUTH_USER'] == $user) && ( $_SERVER['PHP_AUTH_PW'] == $password);

if (!$is_authorized) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo "Not authorized";
    return false;
}

$request = $_SERVER['REQUEST_URI'];
$segment = explode( '/', $request );

switch ($segment[2]) {
    case 'todo' :
        require 'Todo.php';
        $userTodoClass = new Todo();
        $userTodoClass->{$segment[3]}($segment[4], $segment[5]);
        break;
    case 'user' :
        require 'User.php';
        $userClass = new User();
        $userClass->{$segment[3]}();
        break;
    default:
        return false;

}


