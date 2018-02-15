<?php

/**
 * Возвращает HTML блока товаров
 * @param array $good набор параметров
 * @return string
 */
function getGoodBlock($good) {
	return <<<HTML
	
		<div class="good_item">
			<div class="photo">
				<img src="{$good['PhotoURL']}">
			</div>
			<div class="name"><a href="/show.php?id={$good['ID']}" target="_blank">{$good['Name']}</a></div>
			<div class="price">{$good['Price']}₽</div>
		</div>

HTML;

}

function getMoreBlock() {
	return <<<HTML
	
		<div class="good_more_button">
			<div class="plus">
				+
			</div>
			<div>Загрузить еще 50</div>
		</div>

HTML;

}


/**
 * Возвращает список товаров
 * @param int $page страница. от 1
 * @param int $order имя столбца для сортировки
 * @param int $orderType ASC/DESC
 * @return array
 */
function getGoods($page, $order, $orderType) {

	$mysqli = getMysqli();

	$perPage = 50;

	$limit = ($page - 1) * $perPage;

	$limit = $limit > 0 ? $limit : 0;


	$goods = $mysqli->query("
		select
				SQL_CALC_FOUND_ROWS
				g.ID,
				g.Name,
				g.Description,
				g.PhotoURL,
				g.Price
			from goods g
			
		WHERE
			1
			
		ORDER BY 
			g.{$order} {$orderType}
			
		LIMIT 
			{$limit}, {$perPage}
	  
	");

	$total_count =
		$mysqli->query('select FOUND_ROWS()')->fetch_row()[0];

	$more =
		$total_count > $limit + $perPage;

	return [
		'items' => $goods->fetch_all(MYSQLI_ASSOC),
		'more' =>  $more
	];
}