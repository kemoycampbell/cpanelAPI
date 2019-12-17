<?php

    require_once('../vendor/autoload.php');

    $server='';
    $username ='';
    $password = '';
    $config = new \HostJams\CpanelAPI\Config\Config($server, $username, $password);

    $api = new \HostJams\CpanelAPI\Cpanel\Cpanel($config);

    $api->setModule("Ftp");

    echo print_r( $api->list_ftp() );