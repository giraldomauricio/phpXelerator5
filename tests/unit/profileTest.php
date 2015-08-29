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
        $app = new profiles();
        $this->assertNotNull($app);
    }
    
    public function testLoadRolesForPage() {
        $app = new profiles();
        $app->ds->loadMock('roles_definitions', APP_ROOT.'data/roles_definitions.txt');
        $this->assertTrue($app->checkPage('index/test',1,'read'));
        $this->assertFalse($app->checkPage('index/test',1,'write'));
    }
    
    public function testLoadRolesForObject() {
        $app = new profiles();
        $app->ds->loadMock('roles_definitions', APP_ROOT.'data/roles_definitions.txt');
        $this->assertTrue($app->checkObject('profile',4,'insert'));
        $this->assertFalse($app->checkObject('users',4,'insert'));
    }
    
    public function testDeleteProfileWithEnoughPrivileges() {
        $app = new users();
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
        $app = new users();
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
        $app = new users();
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
        $app = new users();
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
    
}
