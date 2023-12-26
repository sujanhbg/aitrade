<?php
if (isset($_GET['uid']) && $_GET['uid'] == 10023601) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 1000');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
    }
    exit(0);
}
header('Content-Type: application/json; charset=utf-8');
define('__ROOT__', dirname(dirname(__FILE__)));


require dirname(__DIR__) . '/vendor/autoload.php';

foreach (glob(dirname(__DIR__) . '/libfile' . '/*.php') as $file) {
    require_once $file;
}
foreach (glob(dirname(__DIR__) . '/libfile/database' . '/*.php') as $file) {
    require_once $file;
}
foreach (glob(dirname(__DIR__) . '/libfile/utilities' . '/*.php') as $file) {
    require_once $file;
}
$kring = new \kring\core\kring();
date_default_timezone_set('Asia/Dhaka');
$kring->Run_restapi_json();
