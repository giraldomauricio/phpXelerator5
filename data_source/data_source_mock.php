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
    
    public function loadMock($table, $file) {
        $records = array();
        if(file_exists($file)) {
            $records = file($file);
            $this->addTable($table);
            $columns = explode("|", $records[0]);
            $this->setFields($table, $columns);
        }
        for($i=1;$i<count($records);$i++) {
            $row = explode("|", $records[$i]);
            $record = array();
            for($j=0;$j<count($row);$j++) {
                $record[trim($columns[$j])] = trim($row[$j]);
            }
            $this->data->$table->addData($record);
        }
    }
    
    public function addTable($table_name) {
        
        $this->data = (object) array($table_name => new table() );
    }
    
    public function setFields($table_name, $fields_array) {
        foreach ($fields_array as $column) {
            array_push($this->data->$table_name->columns,trim($column));
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