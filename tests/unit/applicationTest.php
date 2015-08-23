<?php
/**
 * Created by PhpStorm.
 * User: murdock
 * Date: 8/9/15
 * Time: 8:54 PM
 */

class applicationTest extends PHPUnit_Framework_TestCase {

    public function testExists() {
        $app = new application();
        $this->assertNotNull($app);
    }

    public function testLoadRoutes() { 
        $app = new application();
        $app->load();
        $this->assertEquals($app->routes["test"],"index/test","Test loading routes");
    }

    public function testRouteProcessing() {
        $app = new application();
        $app->load();
        $app->process("Index","test");
        $this->assertTrue(is_a($app->controller,"index"));
        $this->assertEquals($app->controller->test(),"foo");
        $this->assertEquals($app->controller->table,"foo");
        $this->assertEquals($app->render("index","test"),"Some text inside the view.");
    }

    public function testRouteProcessingWhenDoesntExist() {
        $app = new application();
        $app->load();
        try {
            $app->process("blah","doh");
            $this->assertTrue(false);
        } catch(Exception $e) {
            $this->assertTrue(true);
        }

        $this->assertTrue(is_a($app->controller,"ExceleratorError"));
        $this->assertEquals($app->controller->test(),"error");
        $this->assertEquals($app->controller->table,null);
        $this->assertEquals($app->render("index","test"),"Generic error message.");
    }

    public function testApplicationIndependently() {
        $app = new Index();
        $app->process("index","test");
        $this->assertTrue(is_a($app->controller,"index"));
        $this->assertEquals($app->controller->test(),"foo");
        $this->assertEquals($app->controller->table,"foo");
        $this->assertEquals($app->render("index","test"),"Some text inside the view.");
    }

}
