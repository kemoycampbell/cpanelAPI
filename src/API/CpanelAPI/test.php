<?php

use Symfony\Component\HttpClient\HttpClient;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '../../../../vendor/autoload.php';
require_once __DIR__ . '../../vendor/autoload.php';




$domain = $_ENV['CPANEL_DOMAIN'];
$username = $_ENV['CPANEL_USERNAME'];
$token = $_ENV['CPANEL_TOKEN'];

$config = new \KemoyCampbell\CpanelAPI\API\Config\CpanelConfig($domain, $username, $token);

$api = new \KemoyCampbell\CpanelAPI\API\CpanelAPI\CpanelAPI($config);

$response = $api->request('ServerInformation', 'get_information');

//echo $response->getResponseAsString();

echo $response->getResponseAsJson();

echo "<br/>";

//print_r($response->getResponseAsArray());
