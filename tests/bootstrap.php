<?php

include_once('AutoLoader.php');
// Register the directory to your include files
AutoLoader::registerDirectory('./lib');
AutoLoader::registerDirectory('./data_source');
AutoLoader::registerDirectory('./tests/app/controllers');
AutoLoader::registerDirectory('./tests/app/models');
define("APP_ROOT", "./tests/app/");