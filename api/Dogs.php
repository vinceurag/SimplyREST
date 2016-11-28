<?php
if (!defined('SRKEY')){echo'This file can only be called via the main index.php file, and not directly';exit();}

/**
 * Demo
 *
 * @author Vince Urag
 */
class Dogs extends SR_Controller {

    public function __construct() {
        parent::__construct();
        $this->load_model("model");
    }

    public function get_index() {
        $result = $this->model->getUsers();
        $this->sendResponse($result, HTTP_Status::HTTP_OK);

    }

    public function get_name($a){
        echo $a;
    }

    public function post_index() {
        $myArray = $this->getJsonData();

        $resArray = array("title" => "Hello",
                        "details" => array(
                            "choice 1" => "World",
                            "choice 2" => "Philippines"
                        )
                    );

        if($myArray['success'] == "yes") {
            $this->sendResponse($resArray, 200);
        } else {
            $this->sendResponse(array("title" => "error"), HTTP_Status::HTTP_NOT_FOUND);
        }
    }
}
