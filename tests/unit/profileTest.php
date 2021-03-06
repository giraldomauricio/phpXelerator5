<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of profileTest
 *
 * @author mgiraldo
 */
class profileTest extends PHPUnit_Framework_TestCase {

    public function testLoadClass() {
        $app = new Profiles();
        $this->assertNotNull($app);
    }
    
    public function testLoadRolesForPage() {
        $app = new Profiles();
        $app->ds->loadMock('roles_definitions', APP_ROOT.'data/roles_definitions.txt');
        $this->assertTrue($app->checkPage('index/test',1,'read'));
        $this->assertFalse($app->checkPage('index/test',1,'write'));
    }
    
    public function testLoadRolesForObject() {
        $app = new Users();
        $app->ds->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertTrue($app->login('peter@mail.com', '1234'));
        $app->ds->loadMock('roles_definitions', APP_ROOT.'data/roles_definitions.txt');
        $this->assertTrue($app->checkObject('profile',4,'insert'));
        $this->assertFalse($app->checkObject('users',4,'insert'));
    }
    
    public function testAdminGrantsWorksWithAnyRequest() {
        $app = new Users();
        $app->ds->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertTrue($app->login('admin@mail.com', '1234'));
        $app->ds->loadMock('roles_definitions', APP_ROOT.'data/roles_definitions.txt');
        $this->assertTrue($app->checkObject('foo',3.14,'nah'));
        $this->assertTrue($app->checkObject('star',0.1,'destroyer'));
    }
    
    public function testDeleteProfileWithEnoughPrivileges() {
        $app = new Users();
        $app->ds->loadMock('roles_definitions', APP_ROOT.'data/roles_definitions.txt');
        $app->ds->loadMock('profiles', APP_ROOT.'data/profiles.txt');
        $app->ds->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertTrue($app->login('peter@mail.com', '1234'));
        $app->ds->data["profiles"]->index = "profile_id";
        $app->ds->current_table = "profiles";
        $this->assertEquals(3, $app->ds->recordCount());
        $app->removeProfile(1);
        $app->ds->selectFrom(['profiles']);
        $this->assertEquals(2, $app->ds->recordCount());
    }
    
    public function testCreateProfileWithPrivileges() {
        $app = new Users();
        $app->ds->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertTrue($app->login('linda@mail.com', '1234'));
        $app->ds->loadMock('roles_definitions', APP_ROOT.'data/roles_definitions.txt');
        $app->ds->loadMock('profiles', APP_ROOT.'data/profiles.txt');
        $app->ds->data["profiles"]->index = "profile_id";
        $this->assertEquals(3, $app->ds->recordCount());
        $app->addProfile('A new profile');
        $app->ds->selectFrom(['profiles']);
        $this->assertEquals(4, $app->ds->recordCount());
    }
    
    public function testDeleteProfileWithNotEnoughPrivileges() {
        $app = new Users();
        $app->ds->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertTrue($app->login('linda@mail.com', '1234'));
        $app->ds->loadMock('profiles', APP_ROOT.'data/profiles.txt');
        $app->ds->data["profiles"]->index = "profile_id";
        $app->ds->loadMock('roles_definitions', APP_ROOT.'data/roles_definitions.txt');
        $result = $app->removeProfile(1);
        $this->assertFalse($result, "Can't delete because the profile has not enough privileges");
        $app->ds->selectFrom(['profiles']);
        $this->assertEquals(3, $app->ds->recordCount());
    }
    
    public function testCreateProfileWithNotEnoughPrivileges() {
        $app = new Users();
        $app->ds->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertTrue($app->login('john@mail.com', '1234'));
        $app->ds->loadMock('roles_definitions', APP_ROOT.'data/roles_definitions.txt');
        $app->ds->loadMock('profiles', APP_ROOT.'data/profiles.txt');
        $app->ds->data["profiles"]->index = "profile_id";
        $this->assertEquals(3, $app->ds->recordCount());
        $app->addProfile('A new profile');
        $app->ds->selectFrom(['profiles']);
        $this->assertEquals(3, $app->ds->recordCount());
    }
    
    public function testCreateProfileWithAdminPrivileges() {
        $app = new Users();
        $app->ds->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertTrue($app->login('admin@mail.com', '1234'));
        $app->ds->loadMock('roles_definitions', APP_ROOT.'data/roles_definitions.txt');
        $app->ds->loadMock('profiles', APP_ROOT.'data/profiles.txt');
        $app->ds->data["profiles"]->index = "profile_id";
        $this->assertEquals(3, $app->ds->recordCount());
        $app->addProfile('A new profile');
        $app->ds->selectFrom(['profiles']);
        $this->assertEquals(4, $app->ds->recordCount());
    }
    
    public function testUpdateProfileWithNotEnoughPrivileges() {
        $app = new Users();
        $app->ds->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertTrue($app->login('john@mail.com', '1234'));
        $app->ds->loadMock('roles_definitions', APP_ROOT.'data/roles_definitions.txt');
        $app->ds->loadMock('profiles', APP_ROOT.'data/profiles.txt');
        $app->ds->data["profiles"]->index = "profile_id";
        $this->assertEquals(3, $app->ds->recordCount());
        $res = $app->updateProfile(3,"Blah");
        $this->assertFalse($res);
        $this->assertEquals("Standard user", $app->getProfile(3)->profile_name);
    }
    
    public function testUpdateProfileWithEnoughPrivileges() {
        $app = new Users();
        $app->ds->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertTrue($app->login('peter@mail.com', '1234'));
        $app->ds->loadMock('roles_definitions', APP_ROOT.'data/roles_definitions.txt');
        $app->ds->loadMock('profiles', APP_ROOT.'data/profiles.txt');
        $app->ds->data["profiles"]->index = "profile_id";
        $this->assertEquals(3, $app->ds->recordCount());
        $res = $app->updateProfile(3,"Blah");
        $this->assertTrue($res);
        $this->assertEquals("Blah", $app->getProfile(3)->profile_name);
    }
   
    
    public function testLoadRoleMenus() {
        $app = new Users();
        $app->ds->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertTrue($app->login('john@mail.com', '1234'));
        $app->ds->loadMock('roles_definitions', APP_ROOT.'data/roles_definitions.txt');
        $app->loadMenuItems();
        $this->assertEquals(["Index" => "index/test"],$app->menuItems);
    }
}
