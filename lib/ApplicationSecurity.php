<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApplicationSecurity
 *
 * @author mgiraldo
 */
class ApplicationSecurity {
    //put your code here
    
    var $mock = false;
    
    public static function hashPassword($clear_password) {
        return $clear_password;
    }
    
}
