<?php

/**
 * Database handler - for access and management
 * @author Vince Urag
 */
class DatabaseHandler {

    /**
     * connection object
     */
    private $connection;
    private $last_id;

    function __construct()
    {

    }

    /**
     * Connect to the database
     * @return connection
     */
    public function newConnection() {
        $database = Core::getDbData();
        $this->connection = new mysqli($database['host'], $database['user'], $database['password'], $database['database']);
        if(mysqli_connect_errno()) {
            trigger_error("Error connecting to the database: ".$this->connection->error, E_USER_ERROR);
        }
        return $this->connection;
    }

    /**
     * Close the active connection
     */
    public function closeConnection() {
        $this->connection->close();
    }

    /**
     * Execute the sql query
     */
    public function exec($query) {

        if ($result = $this->connection->query($query)) {
            $this->last_id = $result;
        } else {
            trigger_error('Error executing query: '.$this->connection->error, E_USER_ERROR);
        }

        if(!is_bool($result)) {
            $resultSet = array();
            while($row = $result->fetch_assoc()) {
                $resultSet[] = $row;
            }
            return $resultSet;
        } else {
            return $result;
        }
    }

    /**
     * Update records in the database
     * @param String the table
     * @param array of changes field => value
     * @param array the condition
     * @return bool
     */
    public function update_row($table, $changes, $conditions) {
        $condString = "";
        $changeString = "";
        $paramPlaceholder = array("");
        if(is_array($conditions) && is_array($changes)) {
            $condKeys = array_keys($conditions);
            $changesKeys = array_keys($changes);
            $lastCondKey = array_pop($condKeys);
            $lastChangeKey = array_pop($changesKeys);
            foreach ($changes as $column => $value) {
                // populate the param placeholder
                if(gettype($value) == "string") {
                    $paramPlaceholder[0].="s";
                } else if(gettype($value) == "integer") {
                    $paramPlaceholder[0].="i";
                } else if(gettype($value) == "double") {
                    $paramPlaceholder[0].="d";
                }

                if($column == $lastChangeKey) {
                    $changeString = $changeString."".$column."=?";
                } else {
                    $changeString = $changeString."".$column."=?, ";
                }

                $paramPlaceholder[] =& $changes[$column];
            }
            foreach ($conditions as $column => $value) {
                // populate the param placeholder
                if(gettype($value) == "string") {
                    $paramPlaceholder[0].="s";
                } else if(gettype($value) == "integer") {
                    $paramPlaceholder[0].="i";
                } else if(gettype($value) == "double") {
                    $paramPlaceholder[0].="d";
                }

                if($column == $lastCondKey) {
                    $condString = $condString."".$column."=?";
                } else {
                    $condString = $condString."".$column."=? AND ";
                }

                $paramPlaceholder[] =& $conditions[$column];
            }

            $sqlStatement =  "UPDATE {$table} SET ".$changeString." WHERE ".$condString;

            if($statement = $this->connection->prepare($sqlStatement)) {
                $hits = array();

                call_user_func_array(array($statement, 'bind_param'), $paramPlaceholder);
                $result = $statement->execute();
                return array("status" => $result, "affected_rows"=>$statement->affected_rows);
            } else {
                trigger_error("can't prepare statement: ".$this->connection->error, E_USER_ERROR);
            }

        } else {
            trigger_error("conditions must be in associative array form", E_USER_ERROR);
        }

    }

    /**
     * Gets the number of affected rows from the previous query
     *
     * NOTE: only applicable to queries from exec
     *
     * @return int the number of affected rows
     */
    public function affectedRows()
    {
        return $this->connection->affected_rows;
    }

    /**
     * Get rows based on conditions (associative array)
     * @param  [String] $table      table name
     * @param  [array] $conditions conditions in associative array
     * @return [array]             rows in array form
     */
    public function get_row($table, $conditions) {

        $condString = "";
        $paramPlaceholder = array("");
        $ctrConditions = 0;
        if(is_array($conditions)) {
            $ctrConditions = count($conditions);
            $condKeys = array_keys($conditions);
            $lastCondKey = array_pop($condKeys);
            foreach ($conditions as $column => $value) {
                // populate the param placeholder
                if(gettype($value) == "string") {
                    $paramPlaceholder[0].="s";
                } else if(gettype($value) == "integer") {
                    $paramPlaceholder[0].="i";
                } else if(gettype($value) == "double") {
                    $paramPlaceholder[0].="d";
                }

                if($column == $lastCondKey) {
                    $condString = $condString."BINARY ".$column."=?";
                } else {
                    $condString = $condString."BINARY ".$column."=? AND ";
                }

                $paramPlaceholder[] =& $conditions[$column];
            }

            $sqlStatement =  "SELECT * FROM {$table} WHERE ".$condString;

            if($statement = $this->connection->prepare($sqlStatement)) {
                $hits = array();

                call_user_func_array(array($statement, 'bind_param'), $paramPlaceholder);
                $result = $statement->execute();
                $meta = $statement->result_metadata();

                while ($field = $meta->fetch_field()) {
                    $params[] = &$row[$field->name];
                }

                call_user_func_array(array($statement, 'bind_result'), $params);
                while ($statement->fetch()) {
                    foreach($row as $key => $val) {
                        $c[$key] = $val;
                    }
                    $hits[] = $c;
                }
                $statement->close();
                return $hits;
            } else {
                trigger_error("can't prepare statement: ".$this->connection->error, E_USER_ERROR);
            }

        } else {
            trigger_error("conditions must be in associative array form", E_USER_ERROR);
        }
    }

    /**
     * get value based on conditions
     * @param  [String] $table       [table name]
     * @param  [String] $column_name [column name]
     * @param  [array] $conditions  [conditions in array form]
     * @return [String]              [value]
     */
    public function get_value($table, $column_name, $conditions) {
        $condString = "";
        $paramPlaceholder = array("");
        $ctrConditions = 0;
        if(is_array($conditions)) {
            $ctrConditions = count($conditions);
            $condKeys = array_keys($conditions);
            $lastCondKey = array_pop($condKeys);
            foreach ($conditions as $column => $value) {
                // populate the param placeholder
                if(gettype($value) == "string") {
                    $paramPlaceholder[0].="s";
                } else if(gettype($value) == "integer") {
                    $paramPlaceholder[0].="i";
                } else if(gettype($value) == "double") {
                    $paramPlaceholder[0].="d";
                }

                if($column == $lastCondKey) {
                    $condString = $condString."BINARY ".$column."=?";
                } else {
                    $condString = $condString."BINARY ".$column."=? AND ";
                }

                $paramPlaceholder[] =& $conditions[$column];
            }

            $sqlStatement =  "SELECT {$column_name} FROM {$table} WHERE ".$condString;

            if($statement = $this->connection->prepare($sqlStatement)) {
                $hits = array();

                call_user_func_array(array($statement, 'bind_param'), $paramPlaceholder);
                $result = $statement->execute();
                $statement->store_result();
                $statement->bind_result($column_res);
                if(!is_array($column_res) && $statement->fetch() && ($statement->num_rows == 1)) {
                    return $column_res;
                } else {
                    trigger_error("can't return value: result set has multiple rows, try narrowing your conditions", E_USER_ERROR);
                }
            } else {
                trigger_error("can't prepare statement: ".$this->connection->error, E_USER_ERROR);
            }

        } else {
            trigger_error("conditions must be in associative array form", E_USER_ERROR);
        }
    }

    /**
     * check if row exists based on conditions
     * @param  [String] $table       [table name]
     * @param  [array] $conditions  [conditions in array form]
     * @return [array]              [array of values]
     */
    public function has_row($table, $conditions) {
        $condString = "";
        $paramPlaceholder = array("");
        $ctrConditions = 0;
        if(is_array($conditions)) {
            $ctrConditions = count($conditions);
            $condKeys = array_keys($conditions);
            $lastCondKey = array_pop($condKeys);
            foreach ($conditions as $column => $value) {
                // populate the param placeholder
                if(gettype($value) == "string") {
                    $paramPlaceholder[0].="s";
                } else if(gettype($value) == "integer") {
                    $paramPlaceholder[0].="i";
                } else if(gettype($value) == "double") {
                    $paramPlaceholder[0].="d";
                }

                if($column == $lastCondKey) {
                    $condString = $condString."BINARY ".$column."=?";
                } else {
                    $condString = $condString."BINARY ".$column."=? AND ";
                }

                $paramPlaceholder[] =& $conditions[$column];
            }

            $sqlStatement =  "SELECT * FROM {$table} WHERE ".$condString;

            if($statement = $this->connection->prepare($sqlStatement)) {
                $hits = array();

                call_user_func_array(array($statement, 'bind_param'), $paramPlaceholder);
                $result = $statement->execute();
                $meta = $statement->result_metadata();

                while ($field = $meta->fetch_field()) {
                    $params[] = &$row[$field->name];
                }

                call_user_func_array(array($statement, 'bind_result'), $params);
                while ($statement->fetch()) {
                    foreach($row as $key => $val) {
                        $c[$key] = $val;
                    }
                    $hits[] = $c;
                }
                $statement->close();
                if(empty($hits)){
                    return false;
                } else {
                    return true;
                }
            } else {
                trigger_error("can't prepare statement: ".$this->connection->error, E_USER_ERROR);
            }

        } else {
            trigger_error("conditions must be in associative array form", E_USER_ERROR);
        }
    }

    /**
     * inserts a row based on associative array
     * @param  String $table       table name
     * @param  array $valuesArray associative arrat $arr['column_name'] = $value
     * @return boolean              returns true if successful
     */
    public function insert_row($table, $valuesArray) {
        $condString = "";
        $paramPlaceholder = array("");
        $ctrConditions = 0;
        $colString = "";
        if(is_array($valuesArray)) {
            $ctrConditions = count($valuesArray);
            $condKeys = array_keys($valuesArray);
            $lastCondKey = array_pop($condKeys);
            foreach ($valuesArray as $column => $value) {
                // populate the param placeholder
                if(gettype($value) == "string") {
                    $paramPlaceholder[0].="s";
                } else if(gettype($value) == "integer") {
                    $paramPlaceholder[0].="i";
                } else if(gettype($value) == "double") {
                    $paramPlaceholder[0].="d";
                }

                if($column == $lastCondKey) {
                    $condString = $condString."?";
                    $colString = $colString.$column;
                } else {
                    $condString = $condString."?, ";
                    $colString = $colString.$column.", ";
                }

                $paramPlaceholder[] =& $valuesArray[$column];
            }

            $sqlStatement =  "INSERT INTO {$table}(".$colString.") VALUES(".$condString.")";

            if($statement = $this->connection->prepare($sqlStatement)) {
                call_user_func_array(array($statement, 'bind_param'), $paramPlaceholder);
                $result = $statement->execute();
                if($result){
                    return true;
                } else {
                    trigger_error("Can't insert to database: ".$statement->error, E_USER_ERROR);
                }

            } else {
                trigger_error("can't prepare statement: ".$this->connection->error, E_USER_ERROR);
            }

        } else {
            trigger_error("conditions must be in associative array form", E_USER_ERROR);
        }
    }
}
