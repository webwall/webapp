<?php

define('WEBWALL_WEBROOT', __DIR__);

define('WEBWALL_ROOT', realpath(WEBWALL_WEBROOT . DIRECTORY_SEPARATOR . '..'));

require WEBWALL_ROOT . '/src/Webwall/bootstrap.php';
