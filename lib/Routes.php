<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of routes
 *
 * @author murdock
 */
class Routes {

    var $controller;
    var $action;
    var $query_string;
    var $id;
    var $_default_controller = "Application";
    var $_default_action = "Index";
    var $params = array();
    
    //TODO: Add Linux friendly processing
    public function analizeAndProcessRoutes() {
        $initial_query_string = htmlspecialchars(urldecode($_SERVER["QUERY_STRING"]));
        Logger::debug("Processing Query String: ".$initial_query_string, "Routes", "analizeAndProcessRoutes");
        $request = explode("/", $initial_query_string);
        $this->controller = $this->_default_controller;
        $this->action = $this->_default_action;
        if (count($request)>0 && $request[0] != "") {
            $this->controller = $request[0];
        }
        if (count($request)>1) {
            $this->action = $request[1];
        }
        if (count($request)>2) {
            $this->getQueryString($request[2]);
        }
    }

    public function getQueryString($query_string) {
        $clean_query_string = str_replace("?", "", $query_string);
        $query_string_array = explode("&amp;", $clean_query_string);
        foreach ($query_string_array as $key_value_pair) {
            $key_value_pair_array = explode("=", $key_value_pair);
            if ($key_value_pair_array[0]) {
                $this->params[$key_value_pair_array[0]] = $key_value_pair_array[1];
            }
        }
        if (count($query_string_array) == 1) {
            $this->id = $query_string_array[0];
        }
    }

}