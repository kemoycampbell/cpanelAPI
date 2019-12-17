<?php


namespace HostJams\CpanelAPI\Config;

interface ConfigInterface
{
    /**
     * This function returns the server name which can be an ip or a domain
     * @return string - the name of the server
     */
    public function getServerName():string;

    /**
     * This function returns the username that is use to authenticate the user
     * @return string - the username to authenticate the user
     */
    public function getUsername():string;

    /**
     * This function returns the password
     * @return string the password that is used to authenticate the user
     */
    public function getPassword():string;


    /**
     * This function returns the current port that the user which to connect cpanel to
     * @return int the current port to connect to
     */
    public function getPort():int;

    /**
     * This function returns a list of ports that the cpanel supported
     * @return array a list of ports that are supported
     */
    public function getSupportedPorts():array;


    /**
     * This function checks the configuration setting such as port, username fields, password fields
     * and among other to ensure that they are acceptable
     */
    public function validateConfiguration():void;



}