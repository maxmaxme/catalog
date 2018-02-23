<?php

require_once '../config.php';

$goods = getGoods(1, varStr('sorting'), varStr('sorting_type'));

$sorting = $goods['sorting'];
$sorting_type = $goods['sorting_type'];


if ($goods['items']) {

	// html шаблон сортировки
	$sortingList = getSortingList($sorting, $sorting_type);
	$goodsContent = '';


	// Goods Array to HTML
	foreach ($goods['items'] as $goods_item) {

		$goods_item['Price'] = getPrice($goods_item['Price']);

		$goodsContent .= getTemplate('goods_item', $goods_item);
	}

	$content = getTemplate('goods_list', [
		'sortingList' => $sortingList,
		'sorting' => $sorting,
		'sorting_type' => $sorting_type,
		'goods' => $goodsContent
	]);



} else
	$content = 'Ничего не найдено';

$content .= getPageLoadTime();

echo getTemplate('base', [
	'title' => 'Список товаров',
	'pageTitle' => 'Список товаров',
	'containerClass' => 'fullWidth',
	'content' => $content
]);
