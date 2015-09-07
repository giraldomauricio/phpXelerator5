<?php
/**
 * Created by PhpStorm.
 * User: murdock
 * Date: 8/9/15
 * Time: 8:54 PM
 */

class applicationTest extends PHPUnit_Framework_TestCase {

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
        $app->controller = "index";
        $app->action = "test";
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
            $app->controller = "blah";
            $app->action = "doh";
            $app->process();
            $this->assertTrue(false);
        } catch(Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function testApplicationIndependently() {
        $app = new Index();
        $app->controller = "index";
        $app->action = "test";
        //$app->process();
        $this->assertTrue(is_a($app,"index"));
        $this->assertEquals($app->test(),"FooBar-foo", "Renders the page correclty");
        $this->assertEquals($app->table,"foo");
        $this->assertEquals($app->render("index","test"),"Some text inside the view:FooBar-foo");
    }
    
    public function testDataSourceLoad() {
        $app = new Application();
        $this->assertEquals($app->config["data_source"],"data_source_mock");
        $this->assertTrue(is_a($app->ds, "data_source_mock"));
    }
    
    public function testTemplateManager() {
        $app = new Index();
        $app->controller = "index";
        $app->action = "test";
        $app->process();
        $this->assertTrue(is_a($app,"index"));
        $this->assertEquals($app->table,"foo");
        $html = $app->loadApp();
        $this->assertTrue(strpos($html,"DOCTYPE html>") > 0);
        $this->assertTrue(strpos($html,"<meta charset=\"UTF-8\">") > 0);
        $this->assertTrue(strpos($html,"<title>Title</title>") > 0);
        $this->assertTrue(strpos($html,"Some text inside the view:FooBar-foo") > 0);
        $this->assertTrue(strpos($html,"</html>") > 0);
    }
    
    public function testTemplateManagerWithNoTemplate() {
        $app = new Index();
        $app->controller = "index";
        $app->action = "test";
        $app->template = "template_foo.php";
        $app->process();
        $this->assertTrue(is_a($app,"index"));
        $this->assertEquals($app->table,"foo");
        $html = $app->loadApp();
        $this->assertEquals($html,"");
    }
    
    public function testTemplateManagerWithDifferentTemplate() {
        $app = new Index();
        $app->controller = "index";
        $app->action = "test";
        $app->template = "template_2.php";
        $app->process();
        $this->assertTrue(is_a($app,"index"));
        $this->assertEquals($app->table,"foo");
        $html = $app->loadApp();
        $this->assertTrue(strpos($html,"DOCTYPE html>") > 0);
        $this->assertTrue(strpos($html,"<meta charset=\"UTF-8\">") > 0);
        $this->assertTrue(strpos($html,"<title>A template with a different title</title>") > 0);
        $this->assertTrue(strpos($html,"Some text inside the view:FooBar-foo") > 0);
        $this->assertTrue(strpos($html,"</html>") > 0);
    }

}