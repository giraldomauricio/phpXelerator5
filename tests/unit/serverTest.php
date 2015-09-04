<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class serverTest extends PHPUnit_Framework_TestCase {
    
    public function testLoadClass() {
        $app = new Server();
        $this->assertNotNull($app);
    }
    
    public function testLoadServer() {
        ob_start();
        $_SERVER["QUERY_STRING"] = "Index/test";
        Server::Run();
        $html = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($html, "Some text inside the view:FooBar-foo");
    }
    
    public function testLoadServerWithNonExistentAction() {
        ob_start();
        $_SERVER["QUERY_STRING"] = "Index/foo";
        Server::Run();
        $html = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($html, "");
    }
    
}