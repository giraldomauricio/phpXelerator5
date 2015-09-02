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
        $app = new Index();
        $app->load();
        $this->assertTrue(is_a($app,"Index"));
        $this->assertEquals($app->test(),"FooBar-foo");
        $this->assertEquals($app->table,"foo");
        $this->assertEquals($app->render("index","test"),"Some text inside the view:FooBar-foo");
    }

    public function testRouteProcessingWhenDoesntExist() {
        $app = new Index();
        $app->load();
        try {
            $app->process("blah","doh");
            $this->assertTrue(false);
        } catch(Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function testApplicationIndependently() {
        $app = new Index();
        $app->process("index","test");
        $this->assertTrue(is_a($app,"index"));
        $this->assertEquals($app->test(),"FooBar-foo");
        $this->assertEquals($app->table,"foo");
        $this->assertEquals($app->render("index","test"),"Some text inside the view:FooBar-foo");
    }
    
    public function testDataSourceLoad() {
        $app = new Application();
        $this->assertEquals($app->config["data_source"],"data_source_mock");
        $this->assertTrue(is_a($app->ds, "data_source_mock"));
    }
    
    public function testMVC() {
        
    }

}
