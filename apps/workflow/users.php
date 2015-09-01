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
    var $admin = false;
    
    
    function login($username, $password) {
        $_SESSION["logged_in"] = false;
        $this->ds->selectFrom(['users'])->where(['email' => $username, 'password' => ApplicationSecurity::hashPassword($password)]);
        if($this->ds->recordCount() == 1) {
            $_SESSION["logged_in"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["user_roles"] = $this->ds->data["users"]->data[0]->roles;
            $_SESSION["admin"] = $this->ds->data["users"]->data[0]->admin;
            Logger::debug("User $username logged in.", "users", "login");
            return true;
        } else {
            return false;
        }
    }
            
    function logout() {
        $_SESSION["user_roles"] = null;
        $_SESSION["logged_in"] = false;
    }
    
    function addUser($first_name,$last_name,$email,$password) {
        
        if($this->checkObject("users", $_SESSION["user_roles"], "insert")) {
            $this->ds->addData("users",['name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'password' => $password, 'roles' => '1', 'admin' => 0]);
            Logger::debug("Added ".$email." to the table [users].", "users", "addUser");
            return true;
        } else {
            return false;
        }
        
    }
}