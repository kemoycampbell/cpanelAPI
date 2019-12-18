<?php


namespace HostJams\CpanelAPI\Cpanel;

use HostJams\CpanelAPI\Config\ConfigInterface;
use HostJams\CpanelAPI\Cpanel\Exception\ConnectionException;
use HostJams\CpanelAPI\Cpanel\Exception\CredentialException;
use HostJams\CpanelAPI\Cpanel\Exception\FunctionException;
use HostJams\CpanelAPI\Cpanel\Exception\ModuleException;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * This class allows one to interact with cPanel(cpanel.net) programmatically. This API is capable of working on hosts
 * that uses cpanel on their platform whether it be shared or vpn. Example of well known host providers that use cpanel
 * are Bluehost, Godaddy,Namecheap,HostGators, DreamHost and many others
 * Class Cpanel
 * @package HostJams\CpanelAPI\Cpanel
 * @author  Kemoy Campbell <kemoy@hostjams.com>
 * @version 1.1.0
 */

class Cpanel implements CpanelInterface
{
    private $protocol;
    private $config;
    private $module;
    private $timeout;
    private $lastRequest;
    private $error;
    private $useSSL;
    private $auth;
    private $useUAPI;
    private $function;
    private $action;
    private $securePorts = array(2083,2096,2087);
    private $sslVerifyPeer = false;
    private $sslVerifyHost = false;
    private $outputType;
    private $log;

    private const API2_VERSION = 2;
    private const OUTPUT_STDCLASS = "stdclass";
    private const OUTPUT_JSON = "json";
    private const OUTPUT_ARRAY = "array";

    /**
     * This function initalize the constructor with the default variable values.
     * We also check to see if we can connect to the given host and port here.
     * If we cannot connect then an ConnectionException is thrown
     * Cpanel constructor.
     * @param ConfigInterface $config the cpanel config
     * @throws ConnectionException throw if we cannot connect to the host and port before timeout
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->timeout = 5;
        $this->useSSL = false;
        $this->error = "";
        $this->function = "";

        $this->setOutputTypeToStdClass();

        //by default we are set to use UAPI
        $this->useUAPI();

        //check to see if we can connect to the hostname:port
        $this->testConnection();

        $this->auth = base64_encode($this->config->getUsername().":".$this->config->getPassword());
    }

    /**
     * @inheritDoc
     */
    public function useSSL(): void
    {
        $this->useSSL = true;
    }

    /**
     * @inheritDoc
     */
    public function setModule(string $module): void
    {
        $this->module = $module;
    }


    /**
     * @inheritDoc
     */
    public function useUAPI(): void
    {
        $this->useUAPI = true;
    }

    /**
     * @inheritDoc
     */
    public function useAPI2(): void
    {
        $this->useUAPI = false;
    }

    /**
     * @inheritDoc
     */
    public function request(string $url, array $parameters = array()): ResponseInterface
    {

        //setting up the options parameters
        $options = array();
        $options["headers"] = array("Authorization: Basic"=>$this->auth);
        $options["timeout"] = $this->timeout;
        $options["body"] = $parameters;
        $options['verify_peer'] = $this->sslVerifyPeer;
        $options['verify_host']  = $this->sslVerifyHost;

        //set the method type
        $method = !empty($parameters) ? "POST" : "GET";

        //make the client request and return it
        $client = new CurlHttpClient();
        return $client->request($method, $url, $options);
    }

    /**
     * This function takes the function name of the Cpanel api eg list_ftp and set the full endpoint
     * url depending on whether it is UAPI or API2
     * @param string $function the target Cpanel API function eg list_ftp
     * @return string the full uri for the api endpoint
     */
    private function setApiEndpoint(string $function)
    {
        $this->protocol = in_array($this->config->getPort(), $this->securePorts) ? "https" : "http";
        $this->function = $function;

        //inital URI parameter that can be apply to either UAPI or API2
        $server = $this->config->getServerName();
        $port = $this->config->getPort();
        $this->setAction();
        $uri = "$this->protocol://$server:$port/$this->action";

        //adjust the URI based on the UAPI or API2
        if ($this->useUAPI) {
            $uri.=$this->module."/".$this->function;
        } else {
            $user = $this->config->getUsername();
            $uri.='?cpanel_jsonapi_user=' . $user . '&cpanel_jsonapi_apiversion='.self::API2_VERSION.'
            &cpanel_jsonapi_module=' .$this->module . '&cpanel_jsonapi_func=' . $this->function . '&';
        }

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function trace(string $type = "pretty"):void
    {
        if ($type = "pretty") {
            echo "<pre>".print_r($this->log, true)."</pre>";
        } else {
            print_r($this->log);
        }
    }

    /**
     * This function stores the log result of the api call as well as the
     * last request
     * @param ResponseInterface $response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function log(ResponseInterface $response):void
    {
        $content = $response->getContent();
        $this->log = array(
            "response"=>$response->getInfo(),
            "cpanel"=>json_decode($content, true) //convert to array
        );

        $this->lastRequest = json_decode($content);
    }


    /**
     * This function check if the API error is a module or Function Exception
     */
    private function checkForModuleOrFunctionException():void
    {
        // check for module and function error
        if ($this->hasError()) {
            //check for bad module
            $this->moduleExceptionChecker();

            //check for bad function
            $this->functionExceptionChecker();
        }
    }

    /**
     * @inheritDoc
     */
    public function __call(string $function, array $arguments = array())
    {
        $uri = $this->setApiEndpoint($function);

        //temp fix as the arguments is supplying more than 1D array and we only need 1D
        if (!empty($arguments)) {
            $arguments = $arguments[0];
        }

        //make our request and check for bad credential
        $response = $this->request($uri, $arguments);
        $this->checkCredential($response->getStatusCode());

        //store the log of our request and response
        $this->log($response);

        //check for Exception from the api response
        $this->checkForModuleOrFunctionException();

        //return the output in the user's desired format
        return $this->getOutputResult($response->getContent());
    }

    /**
     * @inheritDoc
     */
    public function getLastRequest(): \stdClass
    {
        return $this->lastRequest;
    }

    /**
     * @inheritDoc
     */
    public function hasError(): bool
    {
        $res = $this->lastRequest;
        if ($res instanceof \stdClass) {
            //parsing out the error
            if (property_exists($res, "errors")) {
                //UAPI error parser
                $this->error = print_r($res->errors, true);
            } elseif (property_exists($res, 'cpanelresult')
                && property_exists($res->cpanelresult, 'error')) {
                //API2 error parser
                $this->error = print_r($res->cpanelresult->error, true);
            }
            return !empty($this->error);
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @inheritDoc
     */
    public function setAction(): void
    {
        if ($this->useUAPI) {
            $this->action = "execute/";
        } else {
            $this->action = "json-api/cpanel/";
        }
    }

    /**
     * @inheritDoc
     */
    public function isConnectionOkay(string $hostname, string $port, string $timeout): bool
    {
        $connection = @fsockopen($hostname, $port, $errno, $errstr, $timeout);
        return  is_resource($connection);
    }

    /**
     * @inheritDoc
     */
    public function testConnection(): void
    {
        $hostname = $this->config->getServerName();
        $port = $this->config->getPort();

        if (!$this->isConnectionOkay($hostname, $port, $this->timeout)) {
            throw new ConnectionException("We were not able to connect to $hostname:$port");
        }
    }


    /**
     * @inheritDoc
     */
    public function checkCredential(int $statusCode): void
    {
        if ($statusCode===401) {
            throw new CredentialException("Bad username and password supplied");
        }
    }

    /**
     * @inheritDoc
     */
    public function moduleExceptionChecker(): void
    {
        $error = strtolower($this->error);
        if (strpos($error, "failed to load module")!==false) {
            throw new ModuleException($this->error);
        }
    }

    /**
     * @inheritDoc
     */
    public function functionExceptionChecker(): void
    {
        $error = strtolower($this->error);
        if (strpos($error, "could not find the function")!==false ||
            strpos($error, "could not find function")!==false) {
            throw new FunctionException($this->error);
        }
    }

    /**
     * @inheritDoc
     */
    public function setSslVerifyPeer(bool $verify): void
    {
        $this->sslVerifyPeer = $verify;
    }

    /**
     * @inheritDoc
     */
    public function setSslVerifyHost(bool $verify): void
    {
        $this->sslVerifyHost = $verify;
    }

    /**
     * This function will returns the output in the desired format
     * @param string $response the json response that we get from the api
     * @return array|string|\stdClass
     */
    private function getOutputResult(string $response)
    {
        if ($this->outputType===self::OUTPUT_JSON) {
            return $response;
        } elseif ($this->outputType===self::OUTPUT_STDCLASS) {
            return json_decode($response);
        }
        //return as array
        return json_decode($response, true);
    }

    /**
     * @inheritDoc
     */
    public function setOutputTypeToArray(): void
    {
        $this->outputType = self::OUTPUT_ARRAY;
    }

    /**
     * @inheritDoc
     */
    public function setOutputTypeToJson(): void
    {
        $this->outputType = self::OUTPUT_JSON;
    }

    /**
     * @inheritDoc
     */
    public function setOutputTypeToStdClass(): void
    {
        $this->outputType = self::OUTPUT_STDCLASS;
    }
}

