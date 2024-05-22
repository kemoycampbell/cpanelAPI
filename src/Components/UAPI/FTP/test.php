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
    \KemoyCampbell\CpanelAPI\API\Config\AuthType::Password
);
$api = new \KemoyCampbell\CpanelAPI\API\CpanelAPI\CpanelAPI($config);

$password="Your password here";
$user = "amir";
$ftp = new \KemoyCampbell\CpanelAPI\Components\UAPI\FTP\FTP($api);

echo $ftp->createFTPAccount(
    $user,
    $disallowDot = true,
    $domain = '',
    $homedir = '',
    $password,
    $passwordHash = '',
    $quota = 0
);
