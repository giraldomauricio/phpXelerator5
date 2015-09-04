<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class routesTest extends PHPUnit_Framework_TestCase {
    
    public function testLoadClass() {
        $app = new Routes();
        $this->assertNotNull($app);
    }

    public function testLoadRoutesBasic() {
        $_SERVER["QUERY_STRING"] = "Index/test";
        $routes = new Routes();
        $routes->analizeAndProcessRoutes();
        $this->assertEquals("Index", $routes->controller);
        $this->assertEquals("test", $routes->action);
    }

    public function testLoadRoutesNoAction() {
        $_SERVER["QUERY_STRING"] = "Index";
        $routes = new Routes();
        $routes->analizeAndProcessRoutes();
        $this->assertEquals("Index", $routes->controller);
        $this->assertEquals("Index", $routes->action);
    }
    
    public function testLoadRoutesNoAnything() {
        $_SERVER["QUERY_STRING"] = "";
        $routes = new Routes();
        $routes->analizeAndProcessRoutes();
        $this->assertEquals("Application", $routes->controller);
        $this->assertEquals("Index", $routes->action);
    }
    
}