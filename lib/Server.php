<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Server
 *
 * @author mgiraldo
 */
class Server {
    //put your code here
    
    public static function Run() {
        Logger::debug("Creating server object", "Server", "Run");
        $router = new Routes();
        $router->analizeAndProcessRoutes();
        if($router->controller && $router->action) {
            Logger::debug("Server controller: [".$router->controller."] action: [".$router->action."]", "Server", "Run");
            $controller = $router->controller;
            $app = new $controller();
            $app->params = $router->params;
            $app->controller = $router->controller;
            $app->action = $router->action;
            print $app->loadApp();
        }
    }
    
    
}
