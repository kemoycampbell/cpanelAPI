<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/10/17
 * Time: 8:34 AM
 */


require_once __DIR__ . '/vendor/autoload.php';

$server = '';
$username = '';
$password = '';

$config = new \hostjams\Cpanel\Config\Config($server,$username,$password);
$api = new \hostjams\Cpanel\API($config);


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


