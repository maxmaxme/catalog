<?php
session_start();
date_default_timezone_set('Europe/Moscow');

//error_reporting(E_ERROR);


define('SITE_PATH', __DIR__ . '/');
define('CFG', SITE_PATH . 'config/');
define('CLS', SITE_PATH . 'cls/');
define('FUNCTIONS', SITE_PATH . 'functions/');
define('TEMPLATES', SITE_PATH . 'templates/');



$functions = array_diff(scandir(FUNCTIONS), array('..', '.'));

foreach($functions as $function_file)
	require_once FUNCTIONS . $function_file;

require_once CFG . 'config.inc.php';
