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

/**
 * Заменяем в шаблоне все места вида {{key}} на значения массива $data['key']
 * @param string $template имя шаблона из templates без .html
 * @param array $data Значения
 * @return string
 */
function getTemplate($template, $data = []) {

	ob_start();
	require TEMPLATES . $template . '.html';
	$template = ob_get_clean();

	return preg_replace_callback('/{{([A-z0-9]+)}}/', function ($val) use ($data) {
		return $data[$val[1]];
	}, $template);



}