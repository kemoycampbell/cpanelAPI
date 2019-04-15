# cPanel API

### Overview

Before I get into detail about how the API works and all, let us review what is cPanel. 

cPanel™ is an online (Linux-based) web hosting control panel that provides a graphical interface and automation tools designed to simplify the process of hosting a web site. cPanel utilizes a 3 tier structure that provides capabilities for administrators, resellers, and end-user website owners to control the various aspects of website and server administration through a standard web browser.

In addition to the GUI, cPanel also has command line and API-based access that allows third party software vendors, web hosting organizations, and developers to automate standard system administration processes. (Wikipedia)

### Who uses Cpanel? 
- GoDaddy
- Namecheap
- BlueHost
- HostGator
- DreamHost
- InMotionHosting
- Simply put, almost all major web hosting companies are using it, even independent developers and web administrators

### For the life of me, why another cPanel API?!

I definitely get it. I am not one to re-invent the wheel, unless it is for learning purposes, but that is not the
the case here. I am aware there are already APIs written in PHP to interact with the cPanel API such as:

- xmlapi-php (https://github.com/CpanelInc/xmlapi-php)
- cpanel-UAPI-php-class (https://github.com/N1ghteyes/cpanel-UAPI-php-class) this was what I used for a year before
deciding to rewrite it)

...among others. I was forced to come up with my own after there was no:

- elegant error handling a.k.a. Custom Exceptions, for bad CURL responses, modules missing, bad credentials, invalid access point / invalid hostname and port number, etc
- simplicity 
- agile approach, ease to change, maintain
- speed - let's face it, using the cpanel in the web interface is slow. The curl request in cpanel-UAPI-php-class, in my opinion, is not optimised, and seems to have unnecessary code.
- API that can easily aid the development of cPanel libraries, or a framework that can be separate concerns independently instead of being forced to include them all, as frameworks usually do. See the lib folder for example.
- active maintenance
- PHP 7 base

### Design principle

To simplify the API calls, methods are called via the PHP magic method, which then convert
the name into a function name for the cPanel API, and pass the request on to the cPanel server.

According to cPanel API documentation, to make an API call you need the following:

- Module name (one you want to use, such as Ftp, Email, etc)
- Function (a specific function or sub-routine of the module)

...and with that said, a call using this API goes like this:

$api->function(parameters if any)

The magic method then takes the function name and the parameters as arguments and
converts them to an equivalent cPanel API query.

### Supported versions

 - UAPI
 - API2
 
 ### Why libraries?
 
 Libraries are Domain specific classes, for example Email, FTP, Backup, etc
 with functions specified and detailed under the cPanel documentation online.
 These are designed to make the flow of your applications easier since
 they all have return types which can be easily managed or compared,
 for example, if we have a function called 'add_ftp' in the FTP class (library), it will return
 true on successfully adding a new user, or an error otherwise.
 
 Hence one could build their application as follows:
 
    if(add_ftp)
        //yah we passed
    else
        //man, i thought you were going to pass......whyyyyyyyyyyyy

...in a nutshell, it is all for convenience and faster development time.

### Usage - using the API natively (without the lib)

```php

    require_once __DIR__ . '/vendor/autoload.php';
    
    $server = '';
    $username = '';
    $password = '';
    
    $config = new \hostjams\Cpanel\Config\Config($server,$username,$password);
    $api = new \hostjams\Cpanel\API($config);
    
    //define the module
    $api->setModule('Ftp');
    
    //printing ftp list
    print_r($api->list_ftp());
```

### Usage - using a chosen library (FTP for demo)

```php
    require_once __DIR__ . '/vendor/autoload.php';
    
    Coming soon. Undergoing architectural changes
    /*$server = '';
    $username = '';
    $password = '';
    
    $config = new \hostjams\Cpanel\Config\Config($server,$username,$password);
   
    $ftp = new \hostjams\Cpanel\lib\FTP($config);
    
    $result = $ftp->list_ftp();
    
    if(!$result)
        print_r($ftp->get_query_error());
    else
        print_r($result);*/
```

### Documentation

I have not get around to finish the documentation for this whole thing as of yet, however,
I tried my best to make the code self-documented.

Here are links to official documentation for the cPanel UAPI and API2:

- UAPI - https://documentation.cpanel.net/display/SDK/Guide+to+UAPI
- API2 - https://documentation.cpanel.net/display/SDK/Guide+to+cPanel+API+2

### Contributions

Contributions are welcome. If you have feedback or experience any issues, feel free to log an issue or submit a pull request.

What is next? Check out the changelog. Still want more info? Feel free to message me.

### License

MIT




