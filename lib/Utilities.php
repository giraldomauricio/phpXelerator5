<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utilities
 *
 * @author murdock
 */
class Utilities {
    
    static function checkSize($file)
    {
        $maxSize = LOG_MAX_SIZE;
        if(filesize($file) > $maxSize)
        {
            rename($file,$file.".".date("YmdhIs"));
        }
    }
    
}
