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

class FTP extends API
{


    public function __construct(Config $config)
    {
        parent::__construct($config);
        $this->useUAPI();
        $this->setModule('Ftp');
    }

    /**
     * This method is use to add a new user FTP account. Upon success
     * true is return otherwise the function return a string containing
     * the error.
     * @param array $parameters - the cpanel parameters
     * @return bool | string - return true if success otherwise a string with the error
     */
    public function add_ftp(array $parameters)
    {
        parent::__call('add_ftp',$parameters);

        if($this->query_has_error())
        {
            return $this->get_query_error();
        }

        return true;
    }


    /**
     * This method list the ftp accounts from the cpanel. If an error occured. Otherwise returned false if an error occured
     * @param array $parameters
     * @return bool|mixed
     */
    public function list_ftp(array $parameters = array())
    {
        $res = parent::__call('list_ftp',$parameters);
        if($this->query_has_error())
        {
            return false;
        }

        return $res;
    }

    /**
     * This method is used to change user ftp password. Return true if the password was successful set or changed. Otherwise
     * the error is returned
     * @param array $parameters
     * @return bool|null
     */
    public function passwd( array $parameters)
    {
        parent::__call('passwd',$parameters);

        if($this->query_has_error())
        {
            return $this->get_query_error();
        }

        return true;
    }

    public function delete_ftp(array $parameters)
    {
        parent::__call('delete_ftp',$parameters);
        if($this->query_has_error())
        {
            return $this->get_query_error();
        }

        return true;
    }




}