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
        if($this->index) {
            $temp_array = array();
            $index_name = $this->index;
            foreach($this->data as $structure) {
                if ($index_value != $structure->$index_name) {
                    array_push($temp_array, $structure);
                }
            }
            $this->data = $temp_array;
        } else {
            throw new Exception("Index not defined.");
        }
    }

    public function searchRecord($field, $value) {
        $item = array();
        foreach($this->data as $structure) {
            if(gettype($structure) == "object" && $value == $structure->$field) {
                array_push($item, $structure);
                //$item = $structure;
                //break;
            }
        }
        return $item;
    }
    
    public function updateRecord($index_value, $new_data) {
        if($this->index) {
            $counter = 0;
            $index_name = $this->index;
            foreach($this->data as $structure) {
                if ($index_value == $structure->$index_name) {
                    break;
                }
                $counter++;
            }
            foreach ($new_data as $column => $value) {
                $this->data[$counter]->$column = $value;
            }
            
        } else {
            throw new Exception("Index not defined.");
        }
        
    }
}

class data_source_mock implements data_source {
    
    var $tables = array();
    var $data = array();
    var $pointer = -1;
    var $current_table;
    var $index_field;
    
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
            $this->data[$table]->addData($record);
        }
        $this->dataCache = $this->data;
    }

    public function searchRecord($table, $field, $value) {
        if(strtolower($field) == "password") {
            Logger::debug("Searching in $table for $field matching ****", "data_source_mock", "searchRecord");
        } else {
            Logger::debug("Searching in $table for $field matching $value", "data_source_mock", "searchRecord");
        }
        $result = $this->data[$table]->searchRecord($field, $value);
        //print_r($result);
        return $result;
    }
    
    public function addTable($table_name) {
        //$this->data[$table_name] = (object) array($table_name => new table_mock() ) ;
        $this->data[$table_name] = new table_mock() ;
    }
    
    public function setFields($table_name, $fields_array) {
        //print_r($this->data);
        foreach ($fields_array as $column) {
            array_push($this->data[$table_name]->columns,trim($column));
        }
    }

    public function selectTable($table_name) {
        $this->current_table = $table_name;
    }

    public function addData($table, $data_array) {
        $this->selectTable($table);
        $this->data[$table]->addData($data_array);
    }

    public function connect($connection_array) {
        
    }

    public function readData() {
        $this->pointer++;
        $table = $this->current_table;
        return $this->data[$table]->getData($this->pointer);
    }

    public function readDataPaged($page) {
        $this->pointer++;
        $table = $this->current_table;
        return $this->data[$table]->getData($this->pointer);
    }

    public function removeData($table, $record_id_value) {
        $this->data[$table]->removeData($record_id_value);
    }

    public function selectDb($db_name) {
        
    }

    public function updateData($table,$record_id,$data) {
        $this->data[$table]->updateRecord($record_id, $data);
    }

    public function selectFrom($table_array) {
        $this->current_table = $table_array[0];
        return $this;
    }

    public function recordCount() {
        $table = $this->current_table;
        return count($this->data[$table]->data);
    }

    public function where($column_array) {
        $this->dataCache = $this->data;
        $table = $this->current_table;
        Logger::debug("Table $table", "data_source_mock", "where", "data_source_mock", "searchRecord");
        $result = array();
        foreach($column_array as $key => $value) {
            $temp_result = $this->searchRecord($table,$key,$value);
            //if($temp_result != null) {
            //    array_push($result, $temp_result);
            if(count($temp_result) > 0) {
                $result = $temp_result;
            } else {
                $result = array();
                break;
            }
            $this->data[$table]->data = $result;
        }
        Logger::debug("Found ".count($result)." records.", "data_source_mock", "where");
        $this->data[$table]->data = array_unique($result, SORT_REGULAR);
        return $this->data[$table]->data;
    }
    
    public function resetData() {
        $this->data = $this->dataCache;
    }

}