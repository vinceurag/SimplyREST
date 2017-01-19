<?php
require_once(APP_PATH."core/database_handler.php");

/**
 * Backbone for all models
 * - must be inherited by all models
 *
 * @author Vince Urag
 */
class SR_Model {

    public $db;

    /**
     * Connect to the database
     */
    public function __construct() {
        include APP_PATH.'config/config.php';
        date_default_timezone_set($config['timezone']);
        $this->db = new DatabaseHandler();
        $this->db->newConnection();
    }
}
