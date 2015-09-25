<?php
/**
 * Server uses the loaded libraries to instantiate the Controller and the Action.
 * Every Controller inherits from Application.
 *
 * Version: 1.0 09/24/2015
 * 
 * @author Mauricio Giraldo <mgiraldo@gmail.com>
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
