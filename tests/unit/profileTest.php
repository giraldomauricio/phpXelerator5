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
}
