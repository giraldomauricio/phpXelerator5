<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class data_source_mysqli implements data_source {

    var $tables = array();
    var $data = array();
    var $pointer = -1;
    var $current_table;
    // MySQLi
    var $db_resource;
    var $sql;
    var $index_field;
    var $config = array();
    
    public function connect($connection_array) {
        $connection_array["dbserver"] = $this->config["server"];
        $connection_array["dbuser"] = $this->config["user_name"];
        $connection_array["dbpassword"] = $this->config["password"];
        $connection_array["dbname"] = $this->config["database"];
        Logger::debug("Connecting to ".$connection_array["dbuser"]."@".$connection_array["dbserver"].":".$connection_array["dbname"], "data_source_mysqli", "connect");
        $this->db_resource = new mysqli($connection_array["dbserver"], $connection_array["dbuser"], $connection_array["dbpassword"], $connection_array["dbname"]);
    }

    public function selectTable($table_name) {
        $this->current_table = $table_name;
    }
    
    public function searchRecord($table, $field, $value) {
        if (strtolower($field) == "password") {
            Logger::debug("Searching in $table for $field matching ****", "data_source_mysqli", "searchRecord");
        } else {
            Logger::debug("Searching in $table for $field matching $value", "data_source_mysqli", "searchRecord");
        }
        $this->ExecuteQuery("SELECT * FROM $table WHERE $field = '" . $this->db_resource->real_escape_string($value) . "'");
        $result = array();
        while (($row = $this->readData())) {
            array_push($result, $row);
        }
        return $result;
    }

    public function addData($table, $data_array) {
        $this->selectTable($table);
        $col_array = array();
        $val_array = array();
        foreach ($data_array as $column => $value) {
            array_push($col_array, $column);
            array_push($val_array, "'" . $this->db_resource->real_escape_string($value) . "'");
        }
        $sql = "INSERT INTO $table (" . implode(",", $col_array) . ") VALUES (" . implode(",", $val_array) . ")";
        $this->ExecuteQuery($sql);
        Logger::debug("Inserting one record in $table", "data_source_mysqli", "searchRecord");
        return $this->db_resource->insert_id;
    }

    public function ExecuteQuery($sql) {
        $this->sql = $sql;
        Logger::debug("Executing query", "data_source_mysqli", "searchRecord");
        $this->db_result = $this->db_resource->query($sql);
    }

    public function readData() {
        return $this->db_result->fetch_object();
    }

    public function readDataPaged($page) {
        // To be defined
    }

    public function removeData($table, $record_id_value) {
        Logger::debug("Deleting one record in $table", "data_source_mysqli", "searchRecord");
        $sql = "DELETE FROM $table WHERE $this->index_field = $record_id_value";
        $this->ExecuteQuery($sql);
    }

    public function selectDb($db_name) {
        // Not used in mysqli
    }

    public function updateData($table, $record_id, $data) {
        $this->selectTable($table);
        $sql_array = array();
        foreach ($data as $column => $value) {
            array_push($sql_array, $column . " = " . $this->db_resource->real_escape_string($value) . "'");
        }
        Logger::debug("Updating one record in $table", "data_source_mysqli", "searchRecord");
        $sql = "UPDATE $table SET " . implode(",", $sql_array) . " WHERE $this->index_field = $record_id";
        $this->ExecuteQuery($sql);
    }

    public function selectFrom($table_array) {
        $this->current_table = $table_array[0];
        if(!isset($this->data[$this->current_table])) {
            $this->data[$this->current_table] = new table();
        }
        $this->sql = "SELECT * FROM " . $table_array[0];
        return $this;
    }

    public function recordCount() {
        $record_count =$this->db_result->num_rows;
        Logger::debug("Returning record count: $record_count", "data_source_mysqli", "searchRecord");
        return $record_count;
    }

    public function where($column_array) {
        $this->sql .= " WHERE ";
        $sql_array = array();
        foreach ($column_array as $column => $value) {
            array_push($sql_array, $column . " = '" . $this->db_resource->real_escape_string($value) . "'");
        }
        $this->sql .= implode(" AND ", $sql_array);
        Logger::debug("Running:".$this->sql, "data_source_mysqli", "searchRecord");
        $this->ExecuteQuery($this->sql);
        $result = array();
        while (($row = $this->readData())) {
            array_push($result, $row);
        }
        //print_r($this);
        if(!isset($this->data[$this->current_table])) {
            $this->data[$this->current_table] = new table();
        }
        $this->data[$this->current_table]->data = $result;
        return $result;
    }

    public function resetData() {
        $this->db_result->data_seek(0);
    }

}