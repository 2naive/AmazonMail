<?php
ini_set('max_execution_time',0);
ini_set('memory_limit', '128M');

require_once( dirname(__DIR__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR .  'ses.class.php');
require_once( dirname(__DIR__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR .  'lib.class.php');

DEFINE('PATH_DIR_LOG', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'log');
DEFINE('PATH_DIR_IMPORT', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'import');

DEFINE('PATH_FILE_SUBSCRIBERS', PATH_DIR_IMPORT . DIRECTORY_SEPARATOR . 'subscribers.txt');
DEFINE('PATH_FILE_MSG_TEXT', PATH_DIR_IMPORT . DIRECTORY_SEPARATOR . 'message.txt');
DEFINE('PATH_FILE_MSG_HTML', PATH_DIR_IMPORT . DIRECTORY_SEPARATOR . 'message.html');
DEFINE('PATH_FILE_CREDENTIALS', PATH_DIR_IMPORT . DIRECTORY_SEPARATOR . 'credentials.csv');

DEFINE('MASK_USERNAME', '{%USERNAME%}');

DEFINE('BACKBONE_ENABLED', TRUE);
