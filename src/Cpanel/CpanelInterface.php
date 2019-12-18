<?php


namespace HostJams\CpanelAPI\Cpanel;

use HostJams\CpanelAPI\Config\ConfigInterface;
use HostJams\CpanelAPI\Cpanel\Exception\ConnectionException;
use HostJams\CpanelAPI\Cpanel\Exception\CredentialException;
use HostJams\CpanelAPI\Cpanel\Exception\FunctionException;
use HostJams\CpanelAPI\Cpanel\Exception\ModuleException;
use mysql_xdevapi\Exception;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface CpanelInterface
{
    /**
     * Set the use SSL to true;
     */
    public function useSSL():void;

    /**
     * This function sets the module type that the user want to use.. Example
     * Email.  To see the list of modules available go to
     * https://documentation.cpanel.net/display/DD/Guide+to+UAPI
     * @param string $module
     */
    public function setModule(string $module):void;


    /**
     * Set to use UAPI
     */
    public function useUAPI():void;

    /**
     * Set to use API2
     */
    public function useAPI2():void;

    /**
     * This method make a request to the supplied url along with the parameters
     * @param string $url the url to make the request to
     * @param array $parameters the parameters to suppled to the url
     * @return ResponseInterface the result of the request
     */
    public function request(string $url, array $parameters = array()):ResponseInterface;

    /**
     * This method is the magic method that will make call to the Cpanel api depending on the module ie
     * UAPI or API and parameters supplied
     * @param string $function the target cpanel functions eg add_ftp
     * @param array $arguments the arguments for the function
     * @return mixed the output result from the call
     */
    public function __call(string $function, array $arguments = array());

    /**
     * This function set the output to array
     */
    public function setOutputTypeToArray():void;

    /**
     * This function set the output to JSON
     */
    public function setOutputTypeToJson():void;

    /**
     * This function set the output to stdClass
     */
    public function setOutputTypeToStdClass():void;

    /**
     * @return \stdClass return the result of the Last result in stdclass
     */
    public function getLastRequest():\stdClass;

    /**
     * @return bool return a boolean expression whether the cpanel request call results in an error;
     */
    public function hasError():bool;

    /**
     * @return string return the errors if any
     */
    public function getError():string;

    /**
     * This method sets the action depending on the type.
     * UAPI action "execute/"
     * API2 action 'json-api/cpanel/
     */
    public function setAction():void;

    /**
     * The function uses fsocket with the server name - ip or domain and port number to see if we can establish
     * a connection
     * @param string $hostname  the host to connect to
     * @param string $port the host's port to connect to
     * @param string $timeout maximum timeout to attempt connection
     * @return bool true if we can connect to the given host and port before the connection timeout
     */
    public function isConnectionOkay(string $hostname, string $port, string $timeout):bool;

    /**
     * This function calls the isConnectionOkay function to see if we can connect to the given hostname and port.
     * If we cant established a connection before the timeout then a ConnectionException is thrown
     * @throws ConnectionException thrown if we cannot established a connection before the timeout
     */
    public function testConnection():void;

    /**
     * This method checks the status code and throws an CredentialException if an
     * invalid credential was supplied
     * @param int $statusCode the status code
     * @throws CredentialException throw if we received a 401 status code
     */
    public function checkCredential(int $statusCode):void;

    /**
     * This function checks whether there was a bad module supplied based on
     * the result that is returned from the api call
     * @throws ModuleException this is thrown if there is a bad module supplied
     */
    public function moduleExceptionChecker():void;

    /**
     * This function checks whether there was an function exception based on the
     * result that is return from the api call
     * @throws FunctionException this is thrown if there is a bad function supplied
     */
    public function functionExceptionChecker():void;

    /**
     * This function set the boolean status of the sslVerifyPeer
     * @param bool $verify a boolean expression
     */
    public function setSslVerifyPeer(bool $verify):void;


    /**
     * This function set the boolean status of the sslVerifyHost
     * @param bool $verify a boolean expression
     */
    public function setSslVerifyHost(bool $verify):void;

    /**
    /**
     * This function is a debugger that prints the log of the request and the cpanel response
     * @param string $type "pretty" to print in <pre>data </pre> format. Default is just regular array output
     */
    public function trace(string $type):void;


}