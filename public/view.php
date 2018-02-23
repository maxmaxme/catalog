<?php
/**
 * @var @mysqli
 */
require_once '../config.php';

$content = '';

$id = varInt('id');

$goods_item = $mysqli->query("
	select
			g.ID,
			g.Name,
			g.Description,
			g.Price,
			g.PhotoURL
		from goods g 
	
	WHERE
		g.ID='{$id}' AND 
		g.Deleted=0
	")->fetch_assoc();


if ($goods_item) {

	$goods_item['Description'] = nl2br(htmlspecialchars($goods_item['Description']));
	$goods_item['Price'] = getPrice($goods_item['Price']);


	$title = $goods_item['Name'];

	$content = getTemplate('goods_view', $goods_item);
}

if ($content) {

	$content .= getPageLoadTime();

	echo getTemplate('base', [
		'title' => $title,
		'content' => $content,
	]);



} else {
	echo getErrorPage(404);
}