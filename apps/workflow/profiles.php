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
    var $menuItems = array();
    
    function loadRolePermissiomForPage($page_name, $role, $permission) {
        $this->page_roles = $this->ds->selectFrom(['roles_definitions'])->where(['role_id' => $role, 'permission_type' => 'page', 'permission_object' => $page_name, 'permission' => $permission])[0];
        $this->roles_loaded = true;
    }
    
    public function loadMenuItems() {
        $menuItems = $this->ds->selectFrom(['roles_definitions'])->where(['role_id' => $_SESSION["user_roles"], 'permission_type' => 'page', 'permission' => "read"]);
        foreach ($menuItems as $item) {
            $this->menuItems[$item->name] = $item->permission_object;
        }
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
        if($_SESSION["admin"] == "1") {
            Logger::debug("Granted admin permissions.");
            return true;
        } else {
            Logger::debug("Searching object: $object_name, role: $role, permission: $permission", "profiles", "checkObject");
            $res = $this->ds->selectFrom(['roles_definitions'])->where(['role_id' => $role, 'permission_type' => 'object', 'permission_object' => $object_name, 'permission' => $permission]);
            if(count($res) > 0) {
                $res = $res[0];
            }        
            if($this->ds->recordCount() > 0) {
                return true;
            } else {
                return false;
            }
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
        if($this->checkObject("profile", $_SESSION["user_roles"], "insert")) {
            $this->ds->addData("profiles",['profile_name' => $profile_name]);
            return true;
        } else {
            return false;
        }
    }
}
