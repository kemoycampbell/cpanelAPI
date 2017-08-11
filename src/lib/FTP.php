<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/8/17
 * Time: 10:49 PM
 */

namespace hostjams\Cpanel\lib;

use hostjams\Cpanel\API;
use hostjams\Cpanel\Config\Config;

class FTP
{
    private $api = null;

    public function __construct(Config $config)
    {
        $this->api = new API($config);
        $this->api->useUAPI();
        $this->api->setModule('Ftp');
    }


}