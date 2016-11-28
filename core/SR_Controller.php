<?php

/**
 * Backbone for all controllers
 * - must be inherited by all controllers
 *
 * @author Vince Urag
 */
class SR_Controller extends SR_Model {


    public function __construct() {
        parent::__construct();
        include APP_PATH."core/HTTP_Status.php";
    }

    /**
     * Load the needed model or library
     * @param  string $class_name name of the model/library you want to load()
     */
    public function load($class_name) {
        if(is_file(APP_PATH."models/".$class_name.".php")) {
            $this->$class_name = new $class_name();
        } else if(is_file(APP_PATH."libraries/".$class_name.".php")) {
            $this->$class_name = new $class_name();
        }
    }

    /**
     * Send the response along with the status code in a json format
     * @param  array $arrayData  (can be formatted) array of values you want to send
     * @param  int $statusCode status code for the response
     * @return array data
     */
    public function sendResponse($arrayData, $statusCode) {
        $data = json_encode($arrayData);
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo $data;
    }

    /**
     * Get the json passed through POST/PUT
     * @return array <the data passed in array form>
     */
    public function getJsonData() {
        return json_decode(file_get_contents('php://input'), true);
    }
}
