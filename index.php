<?php
/* 
 * phpXelerator5 MVC
 * Bootstrap load the Autoloader and then loads all the required directories.
 * Then the constants are loaded and the Server Runs the application.
 * The server instantiates the Controller based on the first parameter passed
 * to Index.php, the seconds is the action and the rest are parameters.
 * 
 * Version: 1.0 09/24/2015
 * 
 * @author Mauricio Giraldo <mgiraldo@gmail.com>
 * 
 */
include_once './bootstrap.php';
Server::Run();