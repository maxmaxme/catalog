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

	return new mysqli($mysqli_config['server'], $mysqli_config['user'], $mysqli_config['pass'], $mysqli_config['db']);

}

function getMemcached() {

	$memcached_config = getConfig()['memcached'];

	$memcache = new Memcached;
	$memcache->addServer($memcached_config['server'], $memcached_config['port']) or die ("Could not connect");

	return $memcache;
}

/**
 * Заменяем в шаблоне все места вида {{key}} на значения массива $data['key']
 * @param string $templateName имя шаблона из templates без .html
 * @param array $data Значения
 * @return string
 */
function getTemplate($templateName, $data = []) {

	$data['fileVersionsJs'] = fileVersions['js'];
	$data['fileVersionsCss'] = fileVersions['css'];

	$memcached = getMemcached();

	$templatePath = TEMPLATES . $templateName . '.html';
	$memcachedKey = 'php_templates_' . fileVersions['templates'] . '_' . $templatePath;

	if (!$template = $memcached->get($memcachedKey)) {
		$template = file_get_contents($templatePath);
		$memcached->set($memcachedKey, $template, 5 * 60); // 5 минут
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
	return isset($_REQUEST[$paramName]) ?
		floatval($_REQUEST[$paramName]) : null;
}

function varStr($paramName) {
	return isset($_REQUEST[$paramName]) ?
		htmlspecialchars($_REQUEST[$paramName]) : null;
}


function getPageLoadTime() {
	return '<div id="page_load_time">' . round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 5) . '</div>';
}