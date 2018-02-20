<?php

function getConfig() {

	$server_configs = [
		'config.server.' . gethostname() . '.php',
		'config.server.php'
	];

	foreach ($server_configs as $server_config) {
		if (file_exists(CFG . $server_config)) {
			return require CFG . $server_config;
			break;
		}
	}

}


function pre($arr) {
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}


function getMysqli() {

	$mysqli_config = getConfig()['db'];

	$mysqli = new mysqli($mysqli_config['server'], $mysqli_config['user'], $mysqli_config['pass'], $mysqli_config['db']);

	if ($mysqli->connect_errno) {
		die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}

	return $mysqli;

}

function getMemcached() {

	$memcached_config = getConfig()['memcached'];

	$memcached = new Memcached;

	if ($memcached_config['enabled']) {
		$memcached->addServer($memcached_config['server'], $memcached_config['port'])
			or die ("Could not connect");
	}

	return $memcached;
}

/**
 * Заменяем в шаблоне все места вида {{key}} на значения массива $data['key']
 * @param string $templateName имя шаблона из templates без .html
 * @param array $data Значения
 * @return string
 */
function getTemplate($templateName, $data = []) {

	global $memcached;

	$data['fileVersionsJs'] = fileVersions['js'];
	$data['fileVersionsCss'] = fileVersions['css'];

	$fileName = $templateName . '.html';
	$templatePath = TEMPLATES . $fileName;
	$memcachedKey = 'template_v' . fileVersions['templates'] . '_' . $fileName;


	if (!$template = $memcached->get($memcachedKey)) {
		$template = file_get_contents($templatePath);
		$memcached->set($memcachedKey, $template, 60 * 60); // на час
	}

	return preg_replace_callback('/{{([A-z0-9]+)}}/', function ($val) use ($data) {
		return isset($data[$val[1]]) ? $data[$val[1]] : '';
	}, $template);

}


function varInt($paramName) {
	return isset($_REQUEST[$paramName]) ?
		intval($_REQUEST[$paramName]) : null;
}
function varFloat($paramName) {
	if (isset($_REQUEST[$paramName])) {
		return floatval(str_ireplace(',', '.', $_REQUEST[$paramName]));
	} else
		return null;
}

function varStr($paramName) {
	return isset($_REQUEST[$paramName]) ?
		htmlspecialchars($_REQUEST[$paramName]) : null;
}


function getPageLoadTime() {
	return '<div id="page_load_time">' . round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 5) . '</div>';
}

function getErrorPage($errorCode) {
	$errors = [
		0 => ['Неизвестная ошибка'],
		404 => ['Страница не найдена', 'HTTP/1.0 404 Not Found']
	];

	if (!$errors[$errorCode])
		$errorCode = 0;

	$title = 'Ошибка ' . $errorCode;

	if ($errors[$errorCode][1]) {
		header($errors[$errorCode][1]);
	}

	return getTemplate('base', [
		'title' => $title,
		'pageTitle' => $title,
		'content' => $errors[$errorCode][0]
	]);


}