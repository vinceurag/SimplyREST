<?php
/**
 * SimplyREST
 *
 * All requests will pass through here...
 *
 * @author Vince Urag
 */

define("APP_PATH", dirname(__FILE__)."/");
define("SRKEY", true);
foreach (glob("api/*.php") as $filename)
{
    include $filename;
}
/**
 * Autoload the needed class
 * @param  String $class_name name of the class
 */
function __autoload($class_name) {
    if(is_file("api/".$class_name.".php")) {
        require_once("api/".$class_name.".php");
    } else if (is_file("core/".$class_name.".php")){
        require_once("core/".$class_name.".php");
    } else if (is_file("models/".$class_name.".php")){
        require_once("models/".$class_name.".php");
    } else if (is_file("libraries/".$class_name.".php")){
        require_once("libraries/".$class_name.".php");
    }
}

// require the core class
require_once("core/core.php");
$core = Core::singleton();
$core->getDbData();

// require the constants
require_once("config/constants.php");


// test installation
// echo $core->testInstallation().APP_PATH;

require_once('core/routes.php');
require_once('config/routes.php');
$routes = new Routes();
foreach ($route as $key => $value) {
    $routes->add($key, $value);
}
$routes->exec();

exit();
