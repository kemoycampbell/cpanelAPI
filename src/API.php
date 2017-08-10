<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/31/17
 * Time: 10:32 PM
 */

namespace hostjams\Cpanel;

use hostjams\Cpanel\Config\Config;
use hostjams\Cpanel\Exception\BadCurlResponse;
use hostjams\Cpanel\Exception\InvalidAccessPoint;
use hostjams\Cpanel\Exception\ModuleMissing;

class API
{
    private $config = null;
    private $module = null;
    private $securePorts = array(2083,2096,2087);
    private $protocol = 'https://';
    private $timeout = 5;
    private $apiVersion = 3;
    private $authType = 'pass';
    const AUTH_PASS = 'pass';
    const AUTH_HASH = 'hash';
    private $lastRequest = null;
    private $queryError = null;
    private $sslVerifyPeer = false;
    private $sslVerifyHost = false;


    /**
     * API constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;

        if (!in_array($this->config->getPort(), $this->securePorts)) {
            //user decided to use an unsecured port
            $this->protocol = 'http://';
        }

        $this->checkAccessPoint();
    }

    /**
     * This method switch the api version to 3 which is equivalent to UAPI
     */
    public function useUAPI()
    {
        $this->apiVersion = 3;
    }

    /**
     * This method switch the api version to 2 which is equivalent to API2
     */
    public function useAPI2()
    {
        $this->apiVersion = 2;
    }

    /**
     * @param string $hostname
     * @param int $port
     * @param int $timeout
     * @return bool
     */
    private function testAccessPoint(string $hostname, int $port, int $timeout):bool
    {
        $socket = @fsockopen($hostname, $port, $errno, $errstr, $timeout);
        return is_resource($socket) ? true: false;
    }

    /**
     * @throws InvalidAccessPoint
     */
    private function checkAccessPoint()
    {
        $hostname = $this->config->getServer();
        $port = $this->config->getPort();

        if (!$this->testAccessPoint($hostname, $port, $this->timeout)) {
            $error = $this->config->getServer().':'.$this->config->getPort();
            throw new InvalidAccessPoint('Could not connect to the provided url and port: '.$error);
        }
    }

    private function moduleValidator()
    {
        if ($this->module === null) {
            throw new ModuleMissing("The cpanel module was not supplied!");
        }
    }

    public function request(string $url, array $fields = array()):string
    {
        if ($this->authType === static::AUTH_PASS) {
            $header[0] = "Authorization: Basic " . base64_encode($this->config->getUsername()
                    .":".$this->config->getPassword()) . "\n\r";
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->sslVerifyPeer);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $this->sslVerifyHost);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201');
        $result = curl_exec($curl);

        if ($result === false) {
            throw new BadCurlResponse("CURL returned an invalid response! check the supplied url and field parameters");
        }

        return $result;
    }

    public function __call($name, $arguments = array())
    {
        //convert the name to lowercase
        $name = strtolower($name);
        $this->moduleValidator();

        $url = $this->protocol.$this->config->getServer().':'.$this->config->getPort().'/json-api/cpanel';

        $param = array('cpanel_jsonapi_user'=>$this->config->getUsername(),
            'cpanel_jsonapi_apiversion'=>$this->apiVersion,
            'cpanel_jsonapi_module'=>$this->module,
            'cpanel_jsonapi_func'=>$name
        );

        $arguments = array_merge($arguments, $param);
        $this->lastRequest = json_decode($this->request($url, $arguments));
        return $this->lastRequest;
    }

    public function queryHasError()
    {
        $res = $this->lastRequest;

        if ($res instanceof \stdClass) {
            if (property_exists($res, 'result') && property_exists($res->result, 'errors') && $res->result->errors) {
                //UAPI error parser
                $this->queryError = print_r($res->result->errors, true);
                return true;
            } elseif (property_exists($res, 'cpanelresult') &&
                property_exists($res->cpanelresult, 'error')  && $res->cpanelresult->error) {
                //UAPI2 error parser
                $this->queryError = print_r($res->cpanelresult->error, true);
                return true;
            }
        }

        return false;
    }

    public function getQueryError()
    {
        if ($this->queryError == null) {
            return false;
        }
        return $this->queryError;
    }




}