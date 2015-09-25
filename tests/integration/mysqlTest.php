<?php

/**
 * Created by PhpStorm.
 * User: murdock
 * Date: 8/9/15
 * Time: 8:54 PM
 */
class mysqlTest extends PHPUnit_Framework_TestCase {

    protected static $config;

    public function setUp() {

        self::$config = [
            "data_source" => "mysqli",
            "server" => "localhost",
            "user_name" => "root",
            "password" => "Mao2013!",
            "database" => "phpxelerator5"
        ];
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $data->index_field = "definition_id";

        $data->ExecuteQuery("TRUNCATE TABLE roles_definitions");
        $data->addData("roles_definitions", ['permission_type' => 'page', 'permission_object' => 'index/test', 'permission' => 'read', 'role_id' => 1, 'name' => 'Index']);
        $data->addData("roles_definitions", ['permission_type' => 'page', 'permission_object' => 'index/test', 'permission' => 'read', 'role_id' => 2, 'name' => 'Index']);
        $data->addData("roles_definitions", ['permission_type' => 'page', 'permission_object' => 'index/test', 'permission' => 'read', 'role_id' => 3, 'name' => 'Index']);
        $data->addData("roles_definitions", ['permission_type' => 'page', 'permission_object' => 'index/test', 'permission' => 'read', 'role_id' => 3, 'name' => 'Index']);
        $data->addData("roles_definitions", ['permission_type' => 'object', 'permission_object' => 'profile', 'permission' => 'insert', 'role_id' => 4, 'name' => 'Profiles']);
        $data->addData("roles_definitions", ['permission_type' => 'object', 'permission_object' => 'profile', 'permission' => 'insert', 'role_id' => 2, 'name' => 'Profiles']);
        $data->addData("roles_definitions", ['permission_type' => 'object', 'permission_object' => 'profile', 'permission' => 'delete', 'role_id' => 4, 'name' => 'Profiles']);
        $data->addData("roles_definitions", ['permission_type' => 'object', 'permission_object' => 'profile', 'permission' => 'update', 'role_id' => 4, 'name' => 'Profiles']);
        $data->addData("roles_definitions", ['permission_type' => 'object', 'permission_object' => 'users', 'permission' => 'insert', 'role_id' => 4, 'name' => 'Users']);
        $data->addData("roles_definitions", ['permission_type' => 'object', 'permission_object' => 'users', 'permission' => 'insert', 'role_id' => 2, 'name' => 'Users']);
        $data->addData("roles_definitions", ['permission_type' => 'object', 'permission_object' => 'users', 'permission' => 'delete', 'role_id' => 4, 'name' => 'Profiles']);
        $data->addData("roles_definitions", ['permission_type' => 'object', 'permission_object' => 'users', 'permission' => 'update', 'role_id' => 4, 'name' => 'Profiles']);
        $data->ExecuteQuery("TRUNCATE TABLE profiles");
        $data->addData("profiles", ['profile_name' => 'Administrator']);
        $data->addData("profiles", ['profile_name' => 'Manager']);
        $data->addData("profiles", ['profile_name' => 'Standard user']);
        $data->ExecuteQuery("TRUNCATE TABLE Users");
        $data->addData("Users", ['first_name' => 'John', 'last_name' => 'Smith', 'email' => 'john@mail.com', 'password' => 1234, 'roles' => 1, 'admin' => 0]);
        $data->addData("Users", ['first_name' => 'Linda', 'last_name' => 'Grace', 'email' => 'linda@mail.com', 'password' => 1234, 'roles' => 2, 'admin' => 0]);
        $data->addData("Users", ['first_name' => 'Peter', 'last_name' => 'Drucker', 'email' => 'peter@mail.com', 'password' => 1234, 'roles' => 4, 'admin' => 0]);
        $data->addData("Users", ['first_name' => 'Admin', 'last_name' => 'Admin', 'email' => 'admin@mail.com', 'password' => 1234, 'roles' => 1, 'admin' => 1]);
    }

    public function testLoadDataInTable() {
        $app = new data_source_mysqli();
        $app->config = self::$config;
        $app->connect("");
        $app->index_field = "user_id";
        $app->selectFrom(["users"])->where(["email" => "foo@bar.com"]);
        $count = $app->recordCount();
        $id = $app->addData("users", ["email" => "foo@bar.com"]);
        $app->selectFrom(["users"])->where(["email" => "foo@bar.com"]);
        $this->assertTrue($app->recordCount() > $count, "We have more records after inserting one.");
        $app->removeData("users", $id);
        $app->selectFrom(["users"])->where(["email" => "foo@bar.com"]);
        $this->assertTrue($app->recordCount() == $count, "After deleting the number of records is the same.");
    }

    public function testProfilesManager() {
        $_SESSION["admin"] = 0;
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $data->index_field = "definition_id";
        $app = new Profiles();
        $app->ds = $data;
        $this->assertTrue($app->checkObject('profile', 4, 'insert'));
        $this->assertFalse($app->checkObject('users', 5, 'insert'));
    }
    
    public function testAdminGrantsWorksWithAnyRequest() {
        $app = new Users();
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $data->index_field = "user_id";
        $app->ds = $data;
        $this->assertTrue($app->login('admin@mail.com', '1234'));
        $this->assertTrue($app->checkObject('foo',3.14,'nah'));
        $this->assertTrue($app->checkObject('star',0.1,'destroyer'));
    }
    
    public function testDeleteProfileWithEnoughPrivileges() {
        $app = new Users();
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $data->index_field = "user_id";
        $app->ds = $data;
        $this->assertTrue($app->login('peter@mail.com', '1234'));
        $app->ds->selectAllFrom("profiles");
        $data->index_field = "profile_id";
        $this->assertEquals(3, $app->ds->recordCount());
        $app->removeProfile(1);
        $app->ds->selectAllFrom("profiles");
        $this->assertEquals(2, $app->ds->recordCount());
    }

    public function testCreateProfileWithPrivileges() {
        $app = new Users();
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $data->index_field = "user_id";
        $app->ds = $data;
        $this->assertTrue($app->login('linda@mail.com', '1234'));
        $app->ds->selectAllFrom("roles_definitions");
        $app->ds->selectAllFrom("profiles");
        $app->ds->data["profiles"]->index = "profile_id";
        $this->assertEquals(3, $app->ds->recordCount());
        $app->addProfile('A new profile');
        $app->ds->selectAllFrom("profiles");
        $this->assertEquals(4, $app->ds->recordCount());
    }
    
    public function testDeleteProfileWithNotEnoughPrivileges() {
        $app = new Users();
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $data->index_field = "user_id";
        $app->ds = $data;
        $this->assertTrue($app->login('linda@mail.com', '1234'));
        $app->ds->data["profiles"]->index = "profile_id";
        $result = $app->removeProfile(1);
        $this->assertFalse($result, "Can't delete because the profile has not enough privileges");
        $app->ds->selectAllFrom("profiles");
        $this->assertEquals(3, $app->ds->recordCount());
    }
    
    ////
    
    
    public function testCreateProfileWithNotEnoughPrivileges() {
        $app = new Users();
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $data->index_field = "user_id";
        $app->ds = $data;
        $this->assertTrue($app->login('john@mail.com', '1234'));
        $app->ds->data["profiles"]->index = "profile_id";
        $app->ds->selectAllFrom("profiles");
        $this->assertEquals(3, $app->ds->recordCount());
        $app->addProfile('A new profile');
        $app->ds->selectAllFrom("profiles");
        $this->assertEquals(3, $app->ds->recordCount());
    }
    
    public function testCreateProfileWithAdminPrivileges() {
        $app = new Users();
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $data->index_field = "user_id";
        $app->ds = $data;
        $app->ds->selectFrom(["roles_definitions"])->where(["1" => "1"]);
        $this->assertTrue($app->login('admin@mail.com', '1234'));
        $app->ds->data["profiles"]->index = "profile_id";
        $app->ds->selectAllFrom("profiles");
        $this->assertEquals(3, $app->ds->recordCount());
        $app->addProfile('A new profile');
        $app->ds->selectAllFrom("profiles");
        $this->assertEquals(4, $app->ds->recordCount());
    }
    
    public function testUpdateProfileWithNotEnoughPrivileges() {
        $app = new Users();
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $data->index_field = "user_id";
        $app->ds = $data;
        $this->assertTrue($app->login('john@mail.com', '1234'));
        $app->ds->data["profiles"]->index = "profile_id";
        $app->ds->selectFrom(["profiles"])->where(["1" => "1"]);
        $this->assertEquals(3, $app->ds->recordCount());
        $res = $app->updateProfile(3,"Blah");
        $this->assertFalse($res);
        $this->assertEquals("Standard user", $app->getProfile(3)->profile_name);
    }
    
    public function testUpdateProfileWithEnoughPrivileges() {
        $app = new Users();
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $data->index_field = "user_id";
        $app->ds = $data;
        $this->assertTrue($app->login('peter@mail.com', '1234'));
        $app->ds->data["profiles"]->index = "profile_id";
        $app->ds->selectAllFrom("profiles");
        $app->ds->data["profiles"]->index = "profile_id";
        $this->assertEquals(3, $app->ds->recordCount());
        $res = $app->updateProfile(3,"Blah");
        $this->assertTrue($res);
        $this->assertEquals("Blah", $app->getProfile(3)->profile_name);
    }
   
    public function testLoadRoleMenus() {
        $app = new Users();
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $data->index_field = "user_id";
        $app->ds = $data;
        $this->assertTrue($app->login('john@mail.com', '1234'));
        $app->loadMenuItems();
        $this->assertEquals(["Index" => "index/test"],$app->menuItems);
    }
    
    //////
    
    
    public function testLoginAndSession() {
        $app = new Users();
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $app->ds = $data;
        $data->index_field = "user_id";
        $this->assertTrue($app->login('linda@mail.com', '1234'));
        $this->assertTrue($_SESSION["logged_in"]);
        $app->ds->resetData();
        $this->assertFalse($app->login('linda@mail.com', '456'));
        $this->assertFalse($_SESSION["logged_in"]);
    }
    
    public function testLogoutAndSession() {
        $app = new Users();
        $_SESSION["logged_in"] = true;
        $this->assertTrue($_SESSION["logged_in"]==true);
        $app->logout();
        $this->assertFalse($_SESSION["logged_in"]==true);
    }
    
    public function testUserRoles() {
        $app = new Users();
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $app->ds = $data;
        $data->index_field = "user_id";
        $app->login('linda@mail.com', '1234');
        $this->assertEquals($_SESSION["user_roles"], 2);
        $app->logout();
        $this->assertEquals($_SESSION["user_roles"],  null);
    }
    
    public function testCreateUsersWithPrivileges() {
        $app = new Users();
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $app->ds = $data;
        $data->index_field = "user_id";
        $this->assertTrue($app->login('linda@mail.com', '1234'));
        $app->ds->data["profiles"]->index = "profile_id";
        $app->ds->selectAllFrom("profiles");
        $this->assertEquals(3, $app->ds->recordCount());
        $app->addUser('Standard','User','user@mail.com','1234');
        $app->ds->selectAllFrom("users");
        $this->assertEquals(5, $app->ds->recordCount());
    }
    
    public function testCreateUsersWithoutPrivileges() {
        $app = new Users();
        $data = new data_source_mysqli();
        $data->config = self::$config;
        $data->connect("");
        $app->ds = $data;
        $data->index_field = "user_id";
        $this->assertTrue($app->login('john@mail.com', '1234'));
        $app->ds->data["profiles"]->index = "profile_id";
        $app->ds->selectAllFrom("profiles");
        $this->assertEquals(3, $app->ds->recordCount());
        $app->addUser('Standard','User','user@mail.com','1234');
        $app->ds->selectAllFrom("users");
        $this->assertEquals(4, $app->ds->recordCount());
    }
}
