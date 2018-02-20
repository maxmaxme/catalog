<?php

require_once '../config.php';

$goods = getGoods(1, varStr('sorting'), varStr('sorting_type'));

$sorting = $goods['sorting'];
$sorting_type = $goods['sorting_type'];


if ($goods['items']) {

	// формирование кнопок для сортировки
	$sortingList = implode(' или ', array_map(function ($key) use ($sorting, $sorting_type) {

		$class = '';

		// Если это текущая сортировка — меняем ASC на DESC и наоборот
		if ($key == $sorting) {

			$type = ($sorting_type == _sorting_types[0]) ?
				_sorting_types[1] : _sorting_types[0];

			$class = 'selected';

		} else {

			$type = _sorting_types[0];

		}

		return '<a class="' . $class . '" href="?sorting=' . $key . '&sorting_type=' . $type . '">' . _sorting[$key] . '</a>';

	}, array_keys(_sorting)));


	$content = '
		<p><a href="/manage.php?act=add" target="_blank" class="btn btn-info">Добавить товар</a></p>

		<div class="sorting">
			<b>Сортировать по:</b>
			' . $sortingList . '
		</div>
		
	
		<div class="goods">';


	foreach ($goods['items'] as $goods_item) {

		$goods_item['Price'] = getPrice($goods_item['Price']);

		$content .= getTemplate('goods_item', $goods_item);
	}


	if ($goods['more']) {
		$content .= getTemplate('goods_getMore', [
			'nextPage' => 2,
			'sorting' => $sorting,
			'sorting_type' => $sorting_type
		]);
	}


	$content .= '</div>';

} else
	$content = 'Ничего не найдено';

echo getTemplate('base', [
	'title' => 'Список товаров',
	'pageTitle' => 'Список товаров',
	'containerClass' => 'fullWidth',
	'content' => $content
]);


echo getPageLoadTime();