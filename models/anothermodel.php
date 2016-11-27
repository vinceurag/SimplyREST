<?php

/**
 * Demo model
 *
 * @author Vince Urag
 */
class AnotherModel extends SR_Model {


    public function __construct() {
        parent::__construct();
    }

    public function getDummyData() {
        return array("Dummy" => "Data");
    }
}
