<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class usersTest extends PHPUnit_Framework_TestCase {
    
    public function testLoadClass() {
        $app = new users();
        $this->assertNotNull($app);
    }
    
    public function testLoginAndSession() {
        $app = new users();
        $app->ds->loadMock('users', APP_ROOT.'data/users.txt');
        $this->assertTrue($app->login('linda@mail.com', '1234'));
        $this->assertTrue($_SESSION["logged_in"]);
        $app->ds->resetData();
        $this->assertFalse($app->login('linda@mail.com', '456'));
        $this->assertFalse($_SESSION["logged_in"]);
    }
    
    public function testLogoutAndSession() {
        $app = new users();
        $_SESSION["logged_in"] = true;
        $this->assertTrue($_SESSION["logged_in"]==true);
        $app->logout();
        $this->assertFalse($_SESSION["logged_in"]==true);
    }
    
    public function testUserRoles() {
        $app = new users();
        $app->ds->loadMock('users', APP_ROOT.'data/users.txt');
        $app->login('linda@mail.com', '1234');
        $this->assertEquals($_SESSION["user_roles"], 2);
        $app->logout();
        $this->assertEquals($_SESSION["user_roles"],  null);
    }
}