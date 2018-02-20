<?php
session_start();
date_default_timezone_set('Europe/Moscow');

//error_reporting(E_ERROR);


define('SITE_PATH', __DIR__ . '/');
define('CFG', SITE_PATH . 'config/');
define('CLS', SITE_PATH . 'cls/');
define('FUNCTIONS', SITE_PATH . 'functions/');
define('TEMPLATES', SITE_PATH . 'templates/');



require_once FUNCTIONS . 'engine.php';
require_once FUNCTIONS . 'goods.php';

require_once CFG . 'config.inc.php';

$memcached = getMemcached();
$mysqli = getMysqli();