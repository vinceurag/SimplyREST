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

    public function load_model($model_name) {
        if(is_file(APP_PATH."models/".$model_name.".php")) {
            $this->$model_name = new $model_name();
        }
    }

    public function sendResponse($arrayData, $statusCode) {
        $data = json_encode($arrayData);
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo $data;
    }

    public function getJsonData() {
        return json_decode(file_get_contents('php://input'), true);
    }
}
