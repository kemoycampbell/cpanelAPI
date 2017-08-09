# CPANEl API

### overview

Before I get into the detail of how the API works and all that stuff. Let us review what is cpanel. 

cPanel is an online (Linux-based) web hosting control panel that provides a graphical interface and automation tools designed to simplify the process of hosting a web site. cPanel utilizes a 3 tier structure that provides capabilities for administrators, resellers, and end-user website owners to control the various aspects of website and server administration through a standard web browser.

In addition to the GUI, cPanel also has command line and API-based access that allows third party software vendors, web hosting organizations, and developers to automate standard system administration processes. - wikipedia 

### Who use Cpanel? 
- GoDaddy
- Namecheap
- BlueHost
- HostGator
- DreamHost
- InMotionHosting
- Simply put almost all major web hosting companies are using it.... even developers and web administrators

### For the life of me, why another Cpanel API?!

I definitely get it. I am not the one to just reinvent the wheel, unless it is for learning purpose. But that is not the
the case here. I am aware that there are already APIs that are written in PHP to interact with the CPANEL such as

- Xmlapi-php https://github.com/CpanelInc/xmlapi-php
- cpanel-UAPI-php-class https://github.com/N1ghteyes/cpanel-UAPI-php-class, this was in what I have been using for a year before
designing to rewrite it.

and among other. I was forced to come up with my own after there were not:

- elegant errors handling aka Custom Exceptions, for example, Bad curl response, module missing, bad credentials, invalid access point especially with invalid hostname and port number and what not
- simplicity 
- Agile, easy to change, maintain
- Faster, let us face it, even using the cpanel in the web interface is long. The curl request in cpanel-UAPI-php-class, in my opinion, is long and seemly to have unnecessary code.
- an API that can easily aids the development of cpanel libraries or even framework that can still be used independently by developers instead of being forced to use them all as frameworks normally does. See the lib folder for example.
- Active maintaining 
- PHP 7 based

### Design design principle
To simplify the API calls, methods are called via the PHP magic method which then convert
the name into function name for the cpanel api and passed the request to the url.

According to Cpanel document, to make an api call it goes like this:

- module name - this is the module that one want to use such as Ftp,Email, etc
- function - the function of the module

with that being said, the call using this api goes like this $api->function(parameters if any)

the magic method then takes the function as name and the parameters as arguments then
converted to the equivalent cpanel api query statement.

### supported versions
 - UAPI
 - API2
 - You can override to use API 1 if you desired but I dont see why you should
 
 ### Why libraries?
 Libraries are actually Domain specific classes for example Email, FTP,Backup, etc
 with their functions, as specified and labelled by the cpanel document, implemented.
 Those are designed to make the flow of the our applications easier since
 they will have return types and among the like that can be easily managed or compare
 for example, if we have a function called add_ftp in the FTP class (library) it will returns
 true on successful added new user and the error otherwise hence one could build their application as follow:
 
    if(add_ftp)
    {
        //yah we passed
    }
    else
    {
        //man, i thought you were going to pass......whyyyyyyyyyyyy
    }

in nutshell, it is all for convent and faster development time.
### Usage using the API natively(without the lib)
```php

    require_once __DIR__ . '/vendor/autoload.php';
    
    $server = '';
    $username = '';
    $password = '';
    
    $config = new \hostjams\Cpanel\Config\Config($server,$username,$password);
    $api = new \hostjams\Cpanel\API($config);
    
    //define the module
    $api->setModule('Ftp');
    
    //listing the ftp accounts
    print_r($api->list_ftp());
```

### Usage using a chosen library(FTP for demo)
```php
    require_once __DIR__ . '/vendor/autoload.php';
        
    $server = '';
    $username = '';
    $password = '';
    
    $config = new \hostjams\Cpanel\Config\Config($server,$username,$password);
   
    $ftp = new \hostjams\Cpanel\lib\FTP($config);
    
    $result = $ftp->list_ftp();
    
    //did it failed?
    if(!$result)
    {
        print_r($ftp->get_query_error());
    }
    
    //passed, show all ftp detail
    else
    {
        print_r($result);
    } 
```

### Documentation
I have not get around to finish the document for the whole thing as yet. However,
I tried my best to make the code self documented. However, here are the documents for the cpanel UAPI and API2

- UAPI - https://documentation.cpanel.net/display/SDK/Guide+to+UAPI
- API2 - https://documentation.cpanel.net/display/SDK/Guide+to+cPanel+API+2
### Contributions
Contributions are welcome. If you have feedback or experience issues, feel free to log a issue.
what is next? check out the changelog.... still want more info, feel free to message me.

### License
Use as you see fit




