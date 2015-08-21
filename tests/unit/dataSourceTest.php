<?php
/**
 * Created by PhpStorm.
 * User: murdock
 * Date: 8/9/15
 * Time: 8:54 PM
 */

class dataSourceTest extends PHPUnit_Framework_TestCase {
    
    public function testLoadDummyData() {
        $app = new data_source_mock();
        $this->assertNotNull($app);
    }
    
    public function testAddTable() {
        $app = new data_source_mock();
        $app->addTable('foo');
        $this->assertEquals(count($app->data), 1, "Count tables");
    }
    
    public function testLoadColumnsInTable() {
        $app = new data_source_mock();
        $app->addTable('foo');
        $app->setFields('foo',['name','email']);
        $this->assertEquals($app->data->foo->columns[0], 'name', "Get table column 1");
        $this->assertEquals($app->data->foo->columns[1], 'email', "Get table column 2");
    }
    
    public function testLoadDataInTable() {
        $app = new data_source_mock();
        $app->addTable('foo');
        $app->setFields('foo',['name','email']);
        $app->data->foo->addData(array('name'=>'foo', 'email'=>'bar'));
        $app->data->foo->addData(array('name'=>'foo2', 'email'=>'bar2'));
        $this->assertEquals(count($app->data->foo->data), 2, "Get record count");
        $this->assertEquals($app->data->foo->data[0]->name, 'foo', "Get record 1 column 1");
        $this->assertEquals($app->data->foo->data[0]->email, 'bar', "Get record 1 column 2");
    }
    
}