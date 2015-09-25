<?php
// Main application bootstrap. Loads all the necessary directories and
// sets the base constants of the application.
error_reporting(E_ALL);
include_once('AutoLoader.php');
// Register the directory to your include files
AutoLoader::registerDirectory('./lib');
AutoLoader::registerDirectory('./data_source');
AutoLoader::registerDirectory('./spocc/controllers');
AutoLoader::registerDirectory('./spocc/models');
AutoLoader::registerDirectory('./apps');
// Application constants
define("APP_ROOT", "./spocc/");
define("LOG_LEVEL", "DEBUG");
define("LOG_MAX_SIZE", 10485760);
define("LOG_FOLDER", "./log/");
define("ASSETS","/spocc/assets/");

$hosts = array(
    "www.vaporwave.net" => "config/prod.php",
    "localhost" => "config/dev.php",
    "127.0.0.1" => "config/dev.php",
    "test" => "config/test.php"
);