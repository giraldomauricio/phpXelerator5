<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of profiles
 *
 * @author mgiraldo
 */
class profiles extends application {
    var $email;
    var $password;
    var $roles = array();
    var $status;
    var $page_roles;
    var $roles_loaded = false;
    
    function loadRolePermissiomForPage($page_name, $role) {
        $this->page_roles = $this->ds->selectFrom(['roles_definitions'])->where(['roles' => $role, 'page' => $page_name])[0];
        $this->roles_loaded = true;
    }
    
    function checkPage($page_name, $role, $permission) {
        if(!$this->roles_loaded) {
            $this->loadRolePermissiomForPage($page_name, $role);
        }
        print_r($this->page_roles);
        if($this->page_roles->$permission == 1) {
            return true;
        } else {
            return false;
        }
    }
}
