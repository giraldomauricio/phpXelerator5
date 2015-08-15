<?php
/**
 * Created by PhpStorm.
 * User: mgiraldo
 * Date: 8/13/15
 * Time: 4:11 PM
 */

class application {

    var $routes = array();
    var $controller;
    var $valid = false;
    var $html = "";

    function __construct() {
        $this->load();
    }

    function includeFile($fileName) {
        if(file_exists($fileName)) {
            include($fileName);
            return true;
        } else {
            return false;
        }
    }

    function load() {
        $routes = [];
        if(file_exists(APP_ROOT."/config/routes.php")) {
            include(APP_ROOT."/config/routes.php");
            $this->routes = $routes;
        } else {
            throw new Exception("Routes file not found in ".APP_ROOT . "config/routes.php");
        }
    }

    function process($controller, $action) {
        $this->controller = new $controller;
        if(class_exists($controller)) {
            $this->controller = new $controller;
            if(method_exists($this->controller,$action)) {
                $this->valid = true;
                return true;
            }
            else {
                throw new Exception("Action does not exist.");
            }
        } else {
            throw new Exception("Controller does not exist.");
        }
    }

    function render($controller, $action) {
        if($this->valid) {
            ob_start();
            $view_location = APP_ROOT."/views/".$controller."/".$action.".php";
            //include $view_location;
            $this->includeFile($view_location);
            $this->html = ob_get_contents();
            ob_end_clean();
            return $this->html;
        } else {
            throw new Exception("Application is not valid.");
        }
    }
} 