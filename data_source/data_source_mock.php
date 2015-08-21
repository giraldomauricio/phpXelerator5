<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class table {
    var $columns = array();
    var $data = array();
    var $index = "";
    var $indexes = array();
    public function addData($array) {
        array_push($this->data,(object) $array);
    }
}

class data_source_mock implements data_source {
    
    var $tables = array();
    var $data;
    
    public function addTable($table_name) {
        
        $this->data = (object) array($table_name => new table() );
    }
    
    public function setFields($table_name, $fields_array) {
        foreach ($fields_array as $column) {
            array_push($this->data->$table_name->columns,$column);
        }
    }
    
    public function addData() {
        
    }

    public function connect() {
        
    }

    public function readData() {
        
    }

    public function readDataPaged() {
        
    }

    public function removeData() {
        
    }

    public function selectDb() {
        
    }

    public function updateData() {
        
    }

}