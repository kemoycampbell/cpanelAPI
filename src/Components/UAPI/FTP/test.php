<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);










require_once __DIR__ . '/../../../../vendor/autoload.php';
//require_once __DIR__ . '/../vendor/autoload.php';

$username = $_ENV['CPANEL_USERNAME'];
$token = $_ENV['CPANEL_TOKEN'];
$serverDomain = $_ENV['CPANEL_DOMAIN'];




$config = new \KemoyCampbell\CpanelAPI\API\Config\CpanelConfig(
    $serverDomain,
    $username,
    $token,
    \KemoyCampbell\CpanelAPI\API\Config\AuthType::Token
);
$api = new \KemoyCampbell\CpanelAPI\API\CpanelAPI\CpanelAPI($config);

$password="LearningStudent";
$user = "amir3";
$ftp = new \KemoyCampbell\CpanelAPI\Components\UAPI\FTP\FTP($api);

//echo "Creating user ".$user;
//$created = $ftp->createFTPAccount(
//    $user,
//    $disallowDot = true,
//    $domain = '',
//    $homedir = '',
//    $password,
//    $passwordHash = '',
//    $quota = 0
//);

//<br/> is just html for break
//if($created) {
//    echo "<br/> Successfully created! <br/>";
//}

//echo "Deleting ".$user;
//$deleted = $ftp->delete($user);
//
//
//if($deleted) {
//    echo "<br/> User deleted!";
//}

//$ftp->exist("random");

$ftp->getAccounts();

//test in web
