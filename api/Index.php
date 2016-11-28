<?php
if (!defined('SRKEY')){echo'This file can only be called via the main index.php file, and not directly';exit();}

/**
 * Demo
 *
 * @author Vince Urag
 */
class Index extends SR_Controller {

    public function __construct() {
        parent::__construct();
        $this->load_model("model");
    }

    public function get_index() {
        $arrData = array("status" => "successful", "details" => "framework was installed successfully");
        $this->sendResponse($arrData, HTTP_Status::HTTP_OK);
    }
}
