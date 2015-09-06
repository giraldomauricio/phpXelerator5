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
    var $action;
    var $valid = false;
    var $html = "";
    var $loaded = false;
    var $ds;
    var $template = "_template.php";

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

    function process() {
        $action = $this->action;
        if(method_exists($this, $action)) {
            $this->$action();
            $this->valid = true;
            print $this->render(get_class($this), $action);
            return true;
        } else {
            return false;
        }
    }

    function loadApp() {
        Logger::debug("Loading app", $this->controller, "loadApp");
        ob_start();
        $template_location = APP_ROOT . "/views/" . $this->template;
        Logger::debug("Loading template: ".$template_location, $this->controller, "loadApp");
        $this->includeFile($template_location);
        $this->html = ob_get_contents();
        ob_end_clean();
        return $this->html;
        
    }
    
    function render() {
        $controller = $this->controller;
        $action = $this->action;
        ob_start();
        $view_location = APP_ROOT . "/views/" . strtolower($controller) . "/" . strtolower($action) . ".php";
        $this->includeFile($view_location);
        $this->html = ob_get_contents();
        ob_end_clean();
        return $this->html;
        
    }
    
    function Index() {
        // Default action
        return true;
    }

}
