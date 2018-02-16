<?php

require_once '../../config.php';

header('Content-Type: application/json; charset=utf-8');


//todo memcached


$allowed_templates = [
	'goods_item'
];

$templates_path = '../../templates/';

$files = scandir($templates_path);

$templates = [];

foreach ($allowed_templates as $template) {

	$file = $template . '.html';

	if (in_array($file, $files)) {
		$templates[$template] = file_get_contents($templates_path . $file);
	}
}

$templates = json_encode($templates, 256);


echo 'var templates = ' . $templates;