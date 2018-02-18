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


	$content = <<<HTML
		
		<p><a href="/manage.php?act=add" target="_blank" class="btn btn-info"><i class="fas fa-plus"></i> Добавить товар</a></p>

		<div class="sorting">
			<b>Сортировать по:</b>
			{$sortingList}
		</div>
		
	
		<div class="goods">
HTML;

	$content .= implode('', array_map(function ($goods_item) {
		return getTemplate('goods_item', $goods_item);
	}, $goods['items']));

	if ($goods['more'])
		$content .= getMoreBlock(2, $sorting, $sorting_type);

	$content .= '</div>';

} else
	$content = 'Ничего не найдено';

echo getTemplate('base', [
	'title' => 'Список товаров',
	'pageTitle' => 'Список товаров',
	'content' => $content
]);




echo getPageLoadTime();