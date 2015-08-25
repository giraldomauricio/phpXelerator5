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
        echo "\n\r".date("Y-m-d h:i:s")." - [".$class."] - [".$method."] - ".$message."\n\r";
    }

}