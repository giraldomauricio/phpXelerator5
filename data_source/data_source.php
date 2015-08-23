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
    
    function connect($connection_array);
    
    function selectDb($db_name);

    function selectTable($table_name);

    function readData();
    
    function readDataPaged($page);
    
    function addData($table_name, $data_array);
    
    function removeData($table_name, $record_id_value);
    
    function updateData($record_id);
    
}
