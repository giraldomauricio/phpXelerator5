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
    
    public function testLoadDataInTableFromString() {
        $app = new data_source_mock();
        $app->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertEquals($app->data->users->columns[0], 'name', "Get table column 1");
        $this->assertEquals($app->data->users->columns[1], 'last_name', "Get table column 2");
        $this->assertEquals($app->data->users->columns[2], 'email', "Get table column 3");
        $this->assertEquals(count($app->data->users->data), 3, "Get record count");
        $this->assertEquals($app->data->users->data[0]->name, 'John');
        $this->assertEquals($app->data->users->data[0]->last_name, 'Smith');
        $this->assertEquals($app->data->users->data[0]->email, 'john@mail.com');
        $this->assertEquals($app->data->users->data[1]->name, 'Linda');
        $this->assertEquals($app->data->users->data[1]->last_name, 'Grace');
        $this->assertEquals($app->data->users->data[1]->email, 'linda@mail.com');
        $this->assertEquals($app->data->users->data[2]->name, 'Peter');
        $this->assertEquals($app->data->users->data[2]->last_name, 'Drucker');
        $this->assertEquals($app->data->users->data[2]->email, 'peter@mail.com');
    }

    public function testSearchInTableForString() {
        $app = new data_source_mock();
        $app->loadMock('users', APP_ROOT.'data/users.txt');
        $result = $app->searchRecord('users','email', 'john@mail.com');
        $this->assertEquals($result->name,'John');
    }

    public function testDeleteInTableById() {
        $app = new data_source_mock();
        $app->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertEquals(count($app->data->users->data),3);
        $app->data->users->index = "email";
        $app->removeData('users','john@mail.com');
        $this->assertEquals(count($app->data->users->data),2);
    }

    public function testInsertInTable() {
        $app = new data_source_mock();
        $app->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertEquals(count($app->data->users->data),3);
        $app->addData('users',array('name'=> 'Susan', 'last_name' => 'Boyle', 'email' => 'susan@mail.com'));
        $this->assertEquals(count($app->data->users->data),4);
    }

    public function testReadDataInTable() {
        $app = new data_source_mock();
        $app->loadMock('users', APP_ROOT.'data/users.txt');
        $record = $app->readData();
        $this->assertEquals($record->name, 'John');
        $this->assertEquals($record->last_name, 'Smith');
        $this->assertEquals($record->email, 'john@mail.com');
        $record = $app->readData();
        $this->assertEquals($record->name, 'Linda');
        $this->assertEquals($record->last_name, 'Grace');
        $this->assertEquals($record->email, 'linda@mail.com');
    }

    public function testSelectAllFromATableWhere() {
        $app = new data_source_mock();
        $app->loadMock('users', APP_ROOT.'data/users.txt');
        $app->selectFrom(['users']);
        $this->assertEquals($app->recordCount(),3);
    }

    public function testSelectSomeFromATableWhereUsesOneFilters() {
        $app = new data_source_mock();
        $app->loadMock('users', APP_ROOT.'data/users.txt');
        $app->selectFrom(['users'])->where(['email' => 'john@mail.com']);
        $this->assertEquals($app->recordCount(),1);
    }
    
}