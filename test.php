<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/10/17
 * Time: 8:34 AM
 */


require_once __DIR__ . '/vendor/autoload.php';

use hostjams\Cpanel\Config\Config;
use hostjams\Cpanel\API\API;

$server = ''; //the ip address of your cpanel server
$username = '';//cpanel username
$password = ''; //cpanel password
$port = 2083;

$config = new Config($server,$username,$password, $port);
$api = new API($config);


//valid module
$api->setModule('Ftp');
$api->setOutputType('stdclass');
print_r($api->list_ftp());
echo '<br/>';

//bad module example
$api->setModule('foo');
$api->setOutputType('stdclass');
print_r($api->list_ftp());


//to test BadCredential Exception, make your username and password an incorrect one


