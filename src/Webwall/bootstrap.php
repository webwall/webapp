<?php

// Global vars used throughout
define('WEBWALL_VERSION', '0.0.1');
define('WEBWALL_PHP_REQUIRED', '5.3.0');
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('NL', "\n");

date_default_timezone_set('UTC');
ini_set('date.timezone', 'UTC');
setlocale(LC_ALL, 'en_US.utf-8');

ini_set('display_errors', true);
@ini_set('cgi.fix_pathinfo', 0);

if(version_compare(PHP_VERSION, WEBWALL_PHP_REQUIRED) == -1) {
  exit('Requires PHP v' . WEBWALL_PHP_REQUIRED);
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
  $_SERVER['DOCUMENT_ROOT'] = substr($_SERVER['SCRIPT_FILENAME'], 0, -strlen($_SERVER['SCRIPT_NAME']));
}

// defined('PATH_LIBRARY') or define('PATH_LIBRARY', realpath(dirname(__file__) . DS . '..' . DS));
// defined('PATH_PUBLIC') or define('PATH_PUBLIC', (is_dir($_SERVER['DOCUMENT_ROOT'])) ? str_replace('/', DS, $_SERVER['DOCUMENT_ROOT']) : null);
// define('PATH_TRUSS', dirname(__FILE__));
// define('PATH_BASE', realpath(PATH_PUBLIC . DS . '..' . DS));
// defined('PATH_APP') or define('PATH_APP', realpath(PATH_BASE . DS . 'app'));

// ini_set('include_path', PATH_TRUSS . PS . ini_get('include_path'));

define('WEBWALL_DIR_THEME', realpath(WEBWALL_ROOT . '/templates'));
define('WEBWALL_DIR_VAR', realpath(WEBWALL_ROOT . '/var'));

if(!is_dir(WEBWALL_DIR_THEME)) {
  die(sprintf("WEBWALL_DIR_THEME invalid (%s)", WEBWALL_DIR_THEME));
}

if(!is_dir(WEBWALL_DIR_VAR) || !is_writable(WEBWALL_DIR_VAR)) {
  die(sprintf("WEBWALL_DIR_VAR invalid (%s)", WEBWALL_DIR_VAR));
}

define('WEBWALL_ENV', 'dev');

require WEBWALL_ROOT . '/vendor/autoload.php';
require 'app.php';
