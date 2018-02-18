<?php

require_once '../../config.php';

header('Content-Type: application/json; charset=utf-8');


$allowed_templates = [
	'goods_item'
];

$templates = [];

$memcached = getMemcached();

foreach ($allowed_templates as $templateName) {

	$fileName = $templateName . '.html';
	$path = TEMPLATES . $fileName;

	$memcachedKey = 'template_v' . fileVersions['templates'] . '_' . $fileName;

	if (!$template = $memcached->get($memcachedKey)) {
		$template = file_get_contents($path);
		$memcached->set($memcachedKey, $template, 60 * 60); // на час
	}

	$templates[$templateName] = $template;
}

$templates = json_encode($templates, 256);


echo 'var templates = ' . $templates;