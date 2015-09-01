<?php
/**
 * Created by PhpStorm.
 * User: murdock
 * Date: 8/9/15
 * Time: 8:54 PM
 */

class pplicationTest extends PHPUnit_Framework_TestCase {

    public function testExists() {
        $app = new Application();
        $this->assertNotNull($app);
    }

    public function testLoadRoutes() { 
        $app = new Application();
        $this->assertTrue($app->loaded);
        $this->assertEquals($app->routes["test"],"index/test","Test loading routes");
        $this->assertEquals($app->config["password"],"bar","Test loading config");
    }

    public function testRouteProcessing() {
        $app = new Application();
        $app->load();
        $app->process("Index","test");
        $this->assertTrue(is_a($app->controller,"index"));
        $this->assertEquals($app->controller->test(),"FooBar-fooBar-foo");
        $this->assertEquals($app->controller->table,"foo");
        $this->assertEquals($app->render("index","test"),"Some text inside the view.");
    }

    public function testRouteProcessingWhenDoesntExist() {
        $app = new Application();
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
        $this->assertEquals($app->controller->test(),"FooBar-fooBar-foo");
        $this->assertEquals($app->controller->table,"foo");
        $this->assertEquals($app->render("index","test"),"Some text inside the view.");
    }
    
    public function testDataSourceLoad() {
        $app = new Application();
        $this->assertEquals($app->config["data_source"],"data_source_mock");
        $this->assertTrue(is_a($app->ds, "data_source_mock"));
    }
    
    public function testMVC() {
        
    }

}
