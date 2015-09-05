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

    public function analizeAndProcessRoutes() {
        //TODO: Add Linux friendly processing
        //$initial_query_string = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        $initial_query_string = htmlspecialchars($_SERVER["QUERY_STRING"]);
        $request = explode("/", $initial_query_string);
        if (count($request)>0 && $request[0] != "") {
            $this->controller = $request[0];
        } else {
            $this->controller = $this->_default_controller;
        }
        if (count($request)>1) {
            $this->action = $request[1];
        } else {
            $this->action = $this->_default_action;
        }
        if (!$this->controller && !$this->action) {
            //TODO: Add rescue
            //rescue::NoDefaultActionAndController ();
            $this->controller = $this->_default_controller;
            $this->action = $this->_default_action;
        }
        //print_r($request);
        if (count($request)>2) {
            //print($request[2]); 
            $this->query_string = $this->GetQueryString($request[2]);
            //print_r($this->query_string);
        }
    }

    public function GetQueryString($query_string) {
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
