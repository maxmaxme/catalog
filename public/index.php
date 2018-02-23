<?php

require_once '../config.php';

$goods = getGoods(1, varStr('sorting'), varStr('sorting_type'));

$sorting = $goods['sorting'];
$sorting_type = $goods['sorting_type'];


if ($goods['items']) {

	$sortingList = '';

	foreach (_sorting as $key => $name) {


		// Если это текущая сортировка — меняем ASC на DESC и наоборот
		if ($key == $sorting) {

			$type = ($sorting_type == 'ASC') ? 'DESC' : 'ASC';
			$class = 'class="selected"';

		} else {
			$type = 'ASC';
			$class = '';
		}

		$sortingList .=
			' <a ' . $class . ' href="?sorting=' . $key . '&sorting_type=' . $type . '">' . _sorting[$key] . '</a> ';

	}

	$content = '
		<p><a href="/manage.php?act=add" target="_blank" class="btn btn-info">Добавить товар</a></p>

		<div class="sorting">
			<b>Сортировать по:</b>
			' . $sortingList . '
		</div>
		
		<div id="params" data-sorting="' . $sorting . '" data-sorting_type="' . $sorting_type . '"></div>
	
		<div class="goods">';


	foreach ($goods['items'] as $goods_item) {

		$goods_item['Price'] = getPrice($goods_item['Price']);

		$content .= getTemplate('goods_item', $goods_item);
	}


	$content .= '</div>';

	$content .= '<div id="loading">Загрузка...</div>';


} else
	$content = 'Ничего не найдено';

$content .= getPageLoadTime();

echo getTemplate('base', [
	'title' => 'Список товаров',
	'pageTitle' => 'Список товаров',
	'containerClass' => 'fullWidth',
	'content' => $content
]);
