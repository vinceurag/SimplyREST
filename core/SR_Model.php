<?php

/**
 * Backbone for all models
 * - must be inherited by all models
 *
 * @author Vince Urag
 */
class SR_Model {

    public $db;

    public function __construct() {
        $this->db = new DatabaseHandler();
        $this->db->newConnection();
    }
}
