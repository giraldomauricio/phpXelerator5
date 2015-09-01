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
        
        $router = new Routes();
        $router->AnalizeAndProcessRoutes();
        
        $app = new Application();
        $app->process($router->controller, $router->action);
        
    }
    
    
}
