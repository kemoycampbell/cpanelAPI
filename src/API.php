<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/31/17
 * Time: 10:32 PM
 */


namespace hostjams\Cpanel;
use hostjams\Cpanel\Exception\BadCurlResponse;
use hostjams\Cpanel\Config\Config;
use hostjams\Cpanel\Exception\InvalidAccessPoint;
use hostjams\Cpanel\Exception\ModuleMissing;


class API
{
    private $config = null;
    private $module = null;
    private $securedPort = array(2083,2096,2087);
    private $protocol = 'https://';
    private $timeout = 5;
    private $api_version = 3;
    private $auth_type = 'pass';
    const AUTH_PASS = 'pass';
    const AUTH_HASH = 'hash';
    private $last_request = null;
    private $query_error = null;


    /**
     * API constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;

        //the user decided to use an unsecured port
        if(!in_array($this->config->getPort(),$this->securedPort))
        {
            $this->protocol = 'http://';
        }

        //verify the access point
        $this->checkAccessPoint();

    }

    /**
     * This method is use to set the module that the developer/user
     * which to use
     * @param string $module - a string with the module name that the developer which to use
     */
    public function setModule(string $module)
    {
        $this->module = $module;
    }

    /**
     * This method switch the api version to 3 which is equivalent to UAPI
     */
    public function useUAPI()
    {
        $this->api_version = 3;
    }

    /**
     * This method switch the api version to 2 which is equivalent to API2
     */
    public function useAPI2()
    {
        $this->api_version = 2;
    }


    /**
     * This method is use to validate that the module has been set and is not null.
     * @throws ModuleMissing - throw ModuleMissing if the module was not set.
     */
    private function moduleValidator()
    {
        if($this->module===null)
        {
            throw new ModuleMissing("The cpanel module was not supplied!");
        }
    }


    /**
     * This method perform the request via curl. It takes the url and fields then build the
     * query. This method also perform the authorization of the user's account either hash or password
     * @param string $url - the url to query
     * @param array $fields - the parameters (optional)
     * @return string - return the result after successful query
     * @throws BadCurlResponse - throw BadCurlResponse if curl returns false
     */
    public function request(string $url,array $fields=array()):string
    {
        if($this->auth_type===static::AUTH_PASS)
        {
            $header[0] = "Authorization: Basic " . base64_encode($this->config->getUsername().":".$this->config->getPassword()) . "\n\r";
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($curl, CURLOPT_HEADER,0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201');
        $result = curl_exec($curl);

        if($result===false)
        {
            throw new BadCurlResponse("CURL returned an invalid response! check the supplied url and field parameters");
        }

        return $result;
    }


    /**
     * The validateAccess point method takes the hostname, port and timeout and
     * use the fsockopen to see if we are able to connect to the access point.
     * @param $hostname - the hostname or ip
     * @param $port - the port we want to connect to
     * @param $timeout - timeout, how long before we give up
     * @return bool - return true if we are able to connect to the hostname and port before time out occured
     * otherwise false
     */
    private function validateAccessPoint(string $hostname,int $port, int $timeout):bool
    {
        $fp = @fsockopen($hostname,$port, $errno, $errstr, $timeout);
        return is_resource($fp) ? true: false;
    }

    /**
     * The checkAccessPoint method use the validAccessPoint method to determines
     * whether the port and hostname is accessible otherwise it throws InvalidAccessPoint Exception
     * @throws InvalidAccessPoint - throw InvalidAccessPoint Exception if we cannot connect to the port and hostname
     */
    private function checkAccessPoint()
    {
        $hostname = $this->config->getServer();
        $port = $this->config->getPort();

        if(!$this->validateAccessPoint($hostname,$port,$this->timeout))
        {
            $msg = $this->config->getServer().':'.$this->config->getPort();
            throw new InvalidAccessPoint("could not connected to the provide url and port: ".$msg);
        }
    }


    /** The __call method make it possible for us to make call to the API
     * via magic methods we can call like $api->someMagic() and the someMagic will
     * be convert to lowercase and passed to the cpanel api as the desired function name
     *
     * @param $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments=array())
    {
       //convert the name to lowercase
        $name = strtolower($name);

        $this->moduleValidator();

        $url = $this->protocol.$this->config->getServer().':'.$this->config->getPort().'/json-api/cpanel';

        $param = array('cpanel_jsonapi_user'=>$this->config->getUsername(),
            'cpanel_jsonapi_apiversion'=>$this->api_version,
            'cpanel_jsonapi_module'=>$this->module,
            'cpanel_jsonapi_func'=>$name
        );


        $arguments = array_merge($arguments,$param);





       $this->last_request = json_decode($this->request($url,$arguments));

       return $this->last_request;

    }

    public function query_has_error()
    {
        $res = $this->last_request;

        if($res instanceof \stdClass)
        {

            //UAPI format
            if(property_exists($res,'result') && property_exists($res->result,'errors') && $res->result->errors)
            {
                $this->query_error = print_r($res->result->errors,true);
                return true;
            }

            //API2 format
            else if(property_exists($res,'cpanelresult') &&
                property_exists($res->cpanelresult,'error')  && $res->cpanelresult->error )
            {
                $this->query_error = print_r($res->cpanelresult->error,true);
                return true;
            }
        }

        return false;
    }

    public function get_query_error()
    {
        if($this->query_error == null)
        {
            return false;
        }
        return $this->query_error;
    }

}