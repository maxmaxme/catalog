<?php

require '../config.php';

$orders = [
	'ID' => 'ID',
	'Price' => 'Цене'
];

$orderTypes = ['ASC', 'DESC'];

$curOrder = in_array($_GET['order'], array_keys($orders)) ? $_GET['order'] : array_keys($orders)[0];
$curOrderType = in_array($_GET['orderType'], $orderTypes) ? $_GET['orderType'] : $orderTypes[0];


$goods = getGoods(1, $curOrder, $curOrderType);


if ($goods['items']) {

	// формирование кнопок для сортировки
	$ordersList = implode(' или ', array_map(function ($key) use ($orders, $orderTypes, $curOrder, $curOrderType) {

		$class = '';

		// Если это текущая сортировка — меняем ASC на DESC и наоборот
		if ($key == $curOrder) {

			$curTypeID = array_search($curOrderType, $orderTypes);
			unset($orderTypes[$curTypeID]);

			$class = 'selected';

		}

		$type = array_shift($orderTypes);

		$order = '?order=' . $key . '&orderType=' . $type;

		return "<a class='{$class}' href='{$order}'>{$orders[$key]}</a>";

	}, array_keys($orders)));


	$content = <<<HTML

		<div class="order">
			<b>Сортировать по:</b>
			{$ordersList}
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