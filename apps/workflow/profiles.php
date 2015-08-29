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
    
    function loadRolePermissiomForPage($page_name, $role, $permission) {
        $this->page_roles = $this->ds->selectFrom(['roles_definitions'])->where(['roles' => $role, 'permission_type' => 'page', 'permission_object' => $page_name, 'permission' => $permission])[0];
        $this->roles_loaded = true;
    }
    
    function checkPage($page_name, $role, $permission) {
        $res = $this->ds->selectFrom(['roles_definitions'])->where(['role_id' => $role, 'permission_type' => 'page', 'permission_object' => $page_name, 'permission' => $permission]);
        if(count($res) > 0) {
            $res = $res[0];
        }        
        if($this->ds->recordCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function checkObject($object_name, $role, $permission) {
        Logger::debug("Searching object: $object_name, role: $role, permission: $permission", "profiles", "checkObject");
        $res = $this->ds->selectFrom(['roles_definitions'])->where(['role_id' => $role, 'permission_type' => 'object', 'permission_object' => $object_name, 'permission' => $permission]);
        //print_r($res);
        if(count($res) > 0) {
            $res = $res[0];
        }        
        if($this->ds->recordCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function removeProfile($profile_id) {
        if($this->checkObject("profile", $_SESSION["user_roles"], "delete")) {
            $this->ds->removeData("profiles", $profile_id);
            return true;
        } else {
            return false;
        }
    }
    
    function addProfile($profile_name) {
        $this->ds->addData("profiles",['profile_name' => $profile_name]);
    }
}
