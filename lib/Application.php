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
        Logger::debug("Trying to include file: ".$fileName, $this->controller, "includeFile");
        if (file_exists($fileName)) {
            include($fileName);
            return true;
        } else {
            Logger::error("Fail to include file: ".$fileName, $this->controller, "includeFile");
            return false;
        }
    }

    function load() {
        Logger::debug("Loading configurations", $this->controller, "load");
        $routes = [];
        if (file_exists(APP_ROOT . "/config/routes.php")) {
            Logger::debug("Loading routes: ".APP_ROOT . "/config/routes.php", $this->controller, "load");
            include(APP_ROOT . "/config/routes.php");
            $this->routes = $routes;
        } else {
            Logger::error("Routes not found: ".APP_ROOT . "/config/routes.php", $this->controller, "load");
            throw new Exception("Routes file not found in " . APP_ROOT . "config/routes.php");
        }
        $config = [];
        if (file_exists(APP_ROOT . "/config/config.php")) {
            Logger::debug("Loading configuration: ".APP_ROOT . "/config/config.php", $this->controller, "load");
            include(APP_ROOT . "/config/config.php");
            $this->config = $config;
        } else {
            Logger::error("Configuration not found: ".APP_ROOT . "/config/config.php", $this->controller, "load");
            throw new Exception("Config file not found in " . APP_ROOT . "config/config.php");
        }
        if ($this->config["data_source"] && class_exists($this->config["data_source"])) {
            Logger::debug("Loading datasource: ".$this->config["data_source"], $this->controller, "load");
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
        $template_location = APP_ROOT . "/views/" . $this->template;
        Logger::debug("Loading template: ".$template_location, $this->controller, "loadApp");
        ob_start();
        $this->includeFile($template_location);
        $this->html = ob_get_contents();
        ob_end_clean();
        return $this->html;
    }
    
    function render() {
        Logger::debug("Rendering contents for ".$this->action, $this->controller, "render");
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
