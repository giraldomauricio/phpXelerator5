<?php

class Rescue {
    
    public static function NoConfigurationAvailable($class) {
        $error = "No configuration available for ".$_SERVER["HTTP_HOST"];
        Logger::error($error, $class);
        print "<div style='color:red'>$error</div>";
    }
    
}