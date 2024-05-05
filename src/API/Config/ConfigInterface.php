<?php

/**
 * This interface is used to define the configuration of the cpanel server
 * Interface ConfigInterface
 *
 * @package kemoycampbell\CpanelAPI\API\Config
 */

namespace KemoyCampbell\CpanelAPI\API\Config;

interface ConfigInterface
{


    /**
     * This function returns the url of the cpanel server
     *
     * @return string - the name of the server
     */
    public function getServerDomain():string;


    /**
     * This function returns the username that is use to authenticate the user
     *
     * @return string - the username to authenticate the user
     */
    public function getUsername():string;


    /**
     * This function returns the port that the user which to connect cpanel to
     *
     * @return integer - the cpanel port
     */
    public function getPort():int;


    /**
     * This function returns the password or token that is used to authenticate the user to the cpanel
     *
     * @return string the password that is used to authenticate the user
     */
    public function getToken():string;

    /**
     * This function returns a list of ports that the cpanel supported
     *
     * @return array a list of ports that are supported
     */
    public function getSupportedCpanelPorts():array;
}//end interface
