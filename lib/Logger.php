<?php
/**
 * Created by PhpStorm.
 * User: murdock
 * Date: 8/24/15
 * Time: 10:42 PM
 */

date_default_timezone_set('UTC');

class Logger {

    public static function debug($message, $class="N/A", $method="N/A") {
        if(LOG_LEVEL == "DEBUG") {
            Utilities::checkSize(LOG_FOLDER."debug.log");
            error_log("\n[DEBUG] ".date("Y-m-d h:i:s")." - [".str_pad($class, 20 , " ")."] - [".str_pad($method, 20 , " ")."] - ".$message."", 3, LOG_FOLDER."debug.log");
        }
    }
    
    public static function error($message, $class="N/A", $method="N/A") {
        if(LOG_LEVEL == "ERROR") {
            Utilities::checkSize(LOG_FOLDER."debug.log");
            error_log("/r/n[ERROR] ".date("Y-m-d h:i:s")." - [".str_pad($class, 20 , " ")."] - [".str_pad($method, 20 , " ")."] - ".$message."", 3, LOG_FOLDER."error.log");
        }
    }

}