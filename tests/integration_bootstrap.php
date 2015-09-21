<?php

include_once('AutoLoader.php');
// Register the directory to your include files
AutoLoader::registerDirectory('./lib');
AutoLoader::registerDirectory('./data_source');
AutoLoader::registerDirectory('./tests/integration/app/controllers');
AutoLoader::registerDirectory('./tests/integration/app/models');
AutoLoader::registerDirectory('./apps');
define("APP_ROOT", "./tests/integration/app/");
define("LOG_LEVEL", "DEBUG");
define("LOG_MAX_SIZE", 10485760);
define("LOG_FOLDER", "./tests/integration/app/log/");