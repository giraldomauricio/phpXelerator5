<?php

include_once('AutoLoader.php');
// Register the directory to your include files
AutoLoader::registerDirectory('/Users/mgiraldo/Google Drive/2015/Projects/phpXelerator5/lib');
AutoLoader::registerDirectory('/Users/mgiraldo/Google Drive/2015/Projects/phpXelerator5/data_source');
AutoLoader::registerDirectory('/Users/mgiraldo/Google Drive/2015/Projects/phpXelerator5/tests/app/controllers');
AutoLoader::registerDirectory('/Users/mgiraldo/Google Drive/2015/Projects/phpXelerator5/tests/app/models');
AutoLoader::registerDirectory('/Users/mgiraldo/Google Drive/2015/Projects/phpXelerator5/apps');
define("APP_ROOT", "/Users/mgiraldo/Google Drive/2015/Projects/phpXelerator5/tests/app/");
define("LOG_LEVEL", "DEBUG");
define("LOG_MAX_SIZE", 10485760);
define("LOG_FOLDER", "/Users/mgiraldo/Google Drive/2015/Projects/phpXelerator5/tests/app/log/");