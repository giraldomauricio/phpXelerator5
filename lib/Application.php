<?php

/**
 * Created by PhpStorm.
 * User: mgiraldo
 * Date: 8/13/15
 * Time: 4:11 PM
 */
class Application {

    var $routes = array();
    var $config = array();
    var $controller;
    var $valid = false;
    var $html = "";
    var $loaded = false;
    var $ds;

    function __construct() {
        if (!$this->loaded) {
            $this->load();
        }
    }

    function includeFile($fileName) {
        if (file_exists($fileName)) {
            include($fileName);
            return true;
        } else {
            return false;
        }
    }

    function load() {
        $routes = [];
        if (file_exists(APP_ROOT . "/config/routes.php")) {
            include(APP_ROOT . "/config/routes.php");
            $this->routes = $routes;
        } else {
            throw new Exception("Routes file not found in " . APP_ROOT . "config/routes.php");
        }
        $config = [];
        if (file_exists(APP_ROOT . "/config/config.php")) {
            include(APP_ROOT . "/config/config.php");
            $this->config = $config;
        } else {
            throw new Exception("Config file not found in " . APP_ROOT . "config/config.php");
        }
        if ($this->config["data_source"] && class_exists($this->config["data_source"])) {
            $class = $this->config["data_source"];
            $this->ds = new $class;
        }
        $this->loaded = true;
    }

    function process($controller, $action) {
        if (class_exists($controller)) {
            $this->controller = new $controller;
            if (method_exists($this->controller, $action)) {
                $this->controller->$action();
                $this->valid = true;
                return true;
            } else {
                $this->controller = new ExceleratorError();
                throw new Exception("Action does not exist.");
            }
        } else {
            $this->controller = new ExceleratorError();
            throw new Exception("Controller does not exist.");
        }
    }

    function render($controller, $action) {
        if ($this->valid) {
            ob_start();
            $view_location = APP_ROOT . "/views/" . $controller . "/" . $action . ".php";
            $this->includeFile($view_location);
            $this->html = ob_get_contents();
            ob_end_clean();
            return $this->html;
        } else {
            $error = new ExceleratorError();
            return $error->genericError();
        }
    }

    function pipe() {
        
    }

}
