<?php
/**
 * SimplyREST
 *
 * @author Vince Urag
 */

class Core {

    /**
     * Array of config
     * @var array
     */
    private static $config = array();

    /**
     * Array of database configs
     * @var array
     */
    private static $database = array();

    /**
     * Instance of the core class
     *
     */
    private static $core;

    /**
     * Private constructor to prevent it being created directly
     * @access private
     */
    private function __construct() { }


    public static function singleton() {
        if(!isset(self::$core)) {
            $obj = __CLASS__;
            self::$core = new $obj;
        }

        return self::$core;
    }

    public static function testInstallation() {
        return "Successfully installed!";
    }

    public static function getDbData() {

        include APP_PATH."config/database.php";

        self::$database = $db;

        return self::$database;
    }


}
