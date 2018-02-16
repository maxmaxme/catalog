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

		$sortingLink = '?sorting=' . $key . '&sorting_type=' . $type;
		return '<a class="' . $class . '" href="' . $sortingLink . '">' . _sorting[$key] . '</a>';

	}, array_keys(_sorting)));


	$content = <<<HTML

		<div class="sorting">
			<b>Сортировать по:</b>
			{$sortingList}
		</div>
	
		<div class="goods">
HTML;

	$content .= implode('', array_map(function ($good) {
		return getGoodBlock($good);
	}, $goods['items']));

	if (0 && $goods['more'])
		$content .= getMoreBlock();

	$content .= '</div>';

} else
	$content = 'Ничего не найдено';

echo getTemplate('base', [
	'title' => 'Список товаров',
	'pageTitle' => 'Список товаров',
	'content' => $content
]);




echo '<div id="page_load_time">' . round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 5) . '</div>';