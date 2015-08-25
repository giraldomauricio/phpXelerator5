<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class table_mock extends table{
    var $columns = array();
    var $data = array();
    var $index = "";
    var $indexes = array();
    var $findSet = array();
    var $dataCache = array();
    
    public function addData($array) {
        array_push($this->data,(object) $array);
    }

    public function getData($row_number){
        return $this->data[$row_number];
    }

    public function removeData($index_value){
        $temp_array = array();
        $item = null;
        $index_name = $this->index;
        foreach($this->data as $structure) {
            if ($index_value != $structure->$index_name) {
                array_push($temp_array, $structure);
            }
        }
        $this->data = $temp_array;
    }

    public function searchRecord($field, $value) {
        $item = null;
        foreach($this->data as $structure) {
            if ($value == $structure->$field) {
                $item = $structure;
                break;
            }
        }
        return $item;
    }
}

class data_source_mock implements data_source {
    
    var $tables = array();
    var $data;
    var $pointer = -1;
    var $current_table;
    
    public function loadMock($table, $file) {
        $records = array();
        $this->current_table = $table;
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

    public function searchRecord($table, $field, $value) {
        Logger::debug("Searching in $table for $field matching $value");
        $result = $this->data->$table->searchRecord($field, $value);
        return $result;
    }
    
    public function addTable($table_name) {
        
        $this->data = (object) array($table_name => new table_mock() );
    }
    
    public function setFields($table_name, $fields_array) {
        foreach ($fields_array as $column) {
            array_push($this->data->$table_name->columns,trim($column));
        }
    }

    public function selectTable($table_name) {
        $this->current_table = $table_name;
    }

    public function addData($table, $data_array) {
        $this->selectTable($table);
        $this->data->$table->addData($data_array);
    }

    public function connect($connection_array) {
        
    }

    public function readData() {
        $this->pointer++;
        $table = $this->current_table;
        return $this->data->$table->getData($this->pointer);
    }

    public function readDataPaged($page) {
        $this->pointer++;
        $table = $this->current_table;
        return $this->data->$table->getData($this->pointer);
    }

    public function removeData($table, $record_id_value) {
        $this->data->$table->removeData($record_id_value);
    }

    public function selectDb($db_name) {
        
    }

    public function updateData($record_id) {
        
    }

    public function selectFrom($table_array) {
        $this->current_table = $table_array[0];
        return $this;
    }

    public function recordCount() {
        $table = $this->current_table;
        return count($this->data->$table->data);
    }

    public function where($column_array) {
        $this->dataCache = $this->data;
        $result = array();
        foreach($column_array as $key => $value) {
            array_push($result, $this->searchRecord($this->current_table,$key,$value));
        }
        $table = $this->current_table;
        $this->data->$table->data = $result;
        return $result;
    }

}