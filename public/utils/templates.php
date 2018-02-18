<?php

require_once '../../config.php';

header('Content-Type: application/json; charset=utf-8');


//todo memcached


$allowed_templates = [
	'goods_item'
];
$files = scandir(TEMPLATES);


$memcached = getMemcached();
$templates_hash = 'js_templates_' . fileVersions['templates'];

if (!$templates = $memcached->get($templates_hash)) {

	$templates = [];

	foreach ($allowed_templates as $template) {

		$file = $template . '.html';

		if (in_array($file, $files)) {
			$templates[$template] = file_get_contents(TEMPLATES . $file);
		}
	}

	$templates = json_encode($templates, 256);

	$memcached->set($templates_hash, $templates, 60*60); // час

}


echo 'var templates = ' . $templates;