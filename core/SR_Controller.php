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
    }

    /**
     * Load the needed model
     * @param  string $model_name name of the model you want to load_model
     */
    public function load_model($model_name) {
        if(is_file(APP_PATH."models/".$model_name.".php")) {
            $this->$model_name = new $model_name();
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
