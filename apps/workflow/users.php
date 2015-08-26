<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of users
 *
 * @author mgiraldo
 */
class users extends profiles {
    
    var $first_name;
    var $last_name;
    var $last_login;
    
    
    function login($username, $password) {
        $_SESSION["logged_in"] = false;
        $this->ds->selectFrom(['users'])->where(['email' => $username, 'password' => ApplicationSecurity::hashPassword($password)]);
        if($this->ds->recordCount() == 1) {
            $_SESSION["logged_in"] = true;
            $_SESSION["user_roles"] = explode(",",$this->ds->data->users->data[0]->roles);
            return true;
        } else {
            return false;
        }
    }
    
    function logout() {
        $_SESSION["user_roles"] = null;
        $_SESSION["logged_in"] = false;
    }
}