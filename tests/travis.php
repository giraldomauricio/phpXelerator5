<?php

include_once('AutoLoader.php');
// Register the directory to your include files
AutoLoader::registerDirectory('./lib/');
AutoLoader::registerDirectory('./apps/');
AutoLoader::registerDirectory('./data_source');
AutoLoader::registerDirectory('./tests/app/controllers');
AutoLoader::registerDirectory('./tests/app/models');
define("APP_ROOT", "/home/travis/build/giraldomauricio/phpXelerator5/tests/app/");
define("LOG_LEVEL", "DEBUG");
define("LOG_MAX_SIZE", 10485760);
define("LOG_FOLDER", "/home/travis/build/giraldomauricio/phpXelerator5/tests/app/log/");