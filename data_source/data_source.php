<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author mgiraldo
 */
interface data_source {
    //put your code here
    
    function connect();
    
    function selectDb();

    function selectTable($table_name);

    function readData();
    
    function readDataPaged();
    
    function addData($table_name, $data_array);
    
    function removeData($table_name, $record_id_value);
    
    function updateData($record_id);
    
}
