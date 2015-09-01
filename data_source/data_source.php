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
    
    // Perform insert and update based on the object properties and not necesarily in parameters
    
    function connect($connection_array);
    
    function selectDb($db_name);

    function selectTable($table_name);

    function readData();
    
    function readDataPaged($page);
    
    function addData($table_name, $data_array);
    
    function removeData($table_name, $record_id_value);
    
    function updateData($table,$record_id,$data);

    function selectFrom($columns_array);

    function recordCount();

}
