<?php

header("Access-Control-Allow-Origin: *");
define('__ROOT__', dirname(dirname(__FILE__)));
session_start();
if (isset($_SESSION['UsrID']) && $_SESSION['UsrID'] == 10023601) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    //exit("Error Reporting ON");
    //echo "Dev Mode ON";
} else {
    error_reporting(0);
    //ini_set('display_errors', 1);
    //ini_set('display_startup_errors', 1);
    //error_reporting(E_ALL);
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
$db = new \kring\database\kdbal();
if ($kring->coreconf("apptype") == "restapi_json2") {
    $kring->Run_restapi_json2();
} elseif ($kring->coreconf("apptype") == "restapi_json") {
    $kring->Run_restapi_json();
} else {
    $kring->Run();
    $db->insert_log();
}
