<?php

include_once('AutoLoader.php');
// Register the directory to your include files
AutoLoader::registerDirectory('./lib/');
AutoLoader::registerDirectory('./tests/app/controllers');
AutoLoader::registerDirectory('./tests/app/models');
define("APP_ROOT", "/home/travis/build/giraldomauricio/phpXelerator5/tests/app/");