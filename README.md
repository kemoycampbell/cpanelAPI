# cPanel API

### Table of Contents
- [Overview](#overview)
- [Who use Cpanel](#who-uses-cpanel)
- [Why another Cpanel API](#why-another-cpanel-api)
- [Design Principles](#design-principle)
- [Support Versions](#supported-versions)
- [Why Components](#why-components)
- [Usage](#using-the-api-natively)
- [Documentation](#documentation)
- [Road map](#roadmap)
- [ChangeLogs](#changelogs)
- [License](#license)
- [Contributions & Feedback](#contributions)

### Overview

Before I get into detail about how the API works and all, let us review what is cPanel. 

cPanel™ is an online (Linux-based) web hosting control panel that provides a graphical interface and automation tools designed to simplify the process of hosting a web site. cPanel utilizes a 3 tier structure that provides capabilities for administrators, resellers, and end-user website owners to control the various aspects of website and server administration through a standard web browser.

In addition to the GUI, cPanel also has command line and API-based access that allows third party software vendors, web hosting organizations, and developers to automate standard system administration processes. (Wikipedia)

### Who uses Cpanel
- GoDaddy
- Namecheap
- BlueHost
- HostGator
- DreamHost
- InMotionHosting
- Simply put, almost all major web hosting companies are using it, even independent developers and web administrators

### Why another cpanel API

I definitely get it. I am not one to re-invent the wheel, unless it is for learning purposes, but that is not the
the case here. I am aware there are already APIs written in PHP to interact with the cPanel API such as:

- xmlapi-php (https://github.com/CpanelInc/xmlapi-php)
- cpanel-UAPI-php-class (https://github.com/N1ghteyes/cpanel-UAPI-php-class) this was what I used for a year before
deciding to rewrite it)

...among others. I was forced to come up with my own after there was no:
- active maintenance and updated
- elegant error handling a.k.a. Custom Exceptions, bad connection based on the supplied hostname and port, bad module supplied, unknown function name, etc
- simplicity 
- agile approach, ease to change, maintain
- speed - let's face it, using the cpanel in the web interface is slow. This api use symfony's HttpClient for speed.
- API that can easily aid the development of cPanel components. Component are separate into classes based on the Cpanel Module categories
- PHP 7 base

### Design principle

To simplify the API calls, Cpanel's Functions are called via the PHP magic method, which then convert
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
 
 ### Why Components
 The concept of components are borrow from Symfony. According to Symfony, "Components are a set of decoupled and reusable 
 PHP libraries." To aids the ease of development and the different use cases of the Cpanel API, we decoupled the Cpanel
 Modules into Components. Why is this important? The components will take care of the necessary configurations of the Module
 such as UAPI vs API2, config setup and provides methods that can easily be work with by utilizing return types and other 
 architecture designs that make them easy to verify, manipulate, catch errors etc
 
Let us say we are working with FTP component. We can have a method as follow:
 
    /**
     * As we can see from the illustrated code below. add_ftp takes some parameters and
       attempt to create a new ftp data. The method returns a boolean expression instead of a json response which
       is the default behavior of the cpanel api request
    */
    if(add_ftp(parameters))
        //yah we passed
    else
        //an error occurred, write the steps you would like to take here

...in a nutshell, it is all for convenience and faster development time.

### Using the api natively

```php

    require_once __DIR__ . '/vendor/autoload.php';
    
    $server = ''; #IP or domain of the cpanel server
    $username = ''; #user name for the cpanel server
    $password = ''; # password for the cpanel server
    
    //cpanel configuration
    $config = new \HostJams\CpanelAPI\Config\Config($server, $username, $password);
    
    $api = new \HostJams\CpanelAPI\Cpanel\Cpanel($config);
    
    //define the module we want to use
    $api->setModule("Ftp");
    
    //call our module with the function
    echo print_r( $api->list_ftp() );
```

### Usage - using a chosen Component (FTP for example)

```php
   In progress
```

### Documentation

Documentation are in progress. The functions and interfaces contains comments explains the function purpose, return type,
exception (if any). I will need to run a php documentation to generate the full document.
Here are links to official documentation for the cPanel UAPI and API2:

Cpanel Official Document
- UAPI - https://documentation.cpanel.net/display/SDK/Guide+to+UAPI
- API2 - https://documentation.cpanel.net/display/SDK/Guide+to+cPanel+API+2

### Roadmap
- Submit package to composer
- Going forward I will be working on implementing the components starting with FTP module and work onward to the other components.
I believe it is much easier to start with components that are most common use such as FTP, Mysql, Domain, Email and the
likes.

### ChangeLogs
Because of this API use the __call() method to translate function into Cpanel function names, the usage is still the same
as version 1.0 However, there has been major changes under the hood which I believe will benefits many of us.

- Current version 1.1
- API request are called using Symfony's HttpClient
- Automatically switched to https when the user provided a secure port number
- variables and methods naming has been renamed to be consistent with that of the Cpanel
- results can now be output in 3 different types namely stdClass, Json or array
- Introduce of Custom Exceptions
    - ConnectionException is thrown when we cannot establish a connection with the hostname and port
    - CredentialException is thrown when an incorrect username and password is supplied for authentication
    - ModuleException is thrown for modules that are not supported by Cpanel
    - FunctionException is thrown for functions that are not supported by Cpanel
    - EmptyPasswordException is thrown when password is not supplied
    - EmptyServerNameException is thrown when server ip or domain is not provided
    - EmptyUsernameException is thrown when username is not provided
    - UnsupportedPortException is thrown when a port that is not supported is provided
 - Introduce interfaces
    - ConfigExceptionInterface
    - ConfigInterface
    - CpanelExceptionInterface
    - CpanelInterface

### License

MIT

### Contributions

Contributions are welcome. If you have feedback or experience any issues, feel free to log an issue or submit a pull request.
Want your own local copy? Go ahead and fork it.

What is next? Check out the changelog. Still want more info? Feel free to message me.



