##hostjams/Cpanel API changelog

###Future / In Progress Features
- Formated the data returned from the curl 
 - create independent classes of all modules/functions from https://documentation.cpanel.net/display/SDK/Guide+to+UAPI and https://documentation.cpanel.net/display/SDK/Guide+to+cPanel+API+2
 - use UAPI for all modules/functions in the independent classes unless specified otherwise by the developer
 through the $api->useAPI()
 
 - Catch errors returned from the api json such as access denied, no module or method exist and throw their own custom
 exception to give the developer a better idea of what error they are dealing with
 
 - more custom exception to better describe the errors
 
 - error logging / debug toggle feature
 
 - support last request
 
 - centeralize all modules classes in one class called cpanel thus we have a full version cpanel which can be called programmatically.
 
 - keep all classes in the lib folder that developer can use them independently if need.... minimum dependency should be Config.php, API.php and the class itself

####current version: 1.0.0
#####Add:

=======

**Exceptions**:

-BadCurlResponse - throw when the curl response return false after a query to a url

-InvalidAccessPoint - throw when we are not able to visit a hostname and port

-ModuleMissing - throw if the module to use have not been defined

**lib (libraries):**

FTP can now added and delete user
        