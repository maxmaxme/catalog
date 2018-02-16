<?php


function getMoreBlock($nextPage, $sorting, $sorting_type) {
	return <<<HTML
	
		<div class="good_more_button" 
			data-next-page="{$nextPage}" 
			data-sorting="{$sorting}" 
			data-sorting-type="{$sorting_type}">
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
 * @param string $sorting имя столбца для сортировки
 * @param string $sorting_type ASC/DESC
 * @return array
 */
function getGoods($page = 1, $sorting = '', $sorting_type = 'ASC') {


	$more = 0;
	$perPage = 20;
	$items = [];
	$limit = ($page - 1) * $perPage;

	$mysqli = getMysqli();

	// order by XXX
	$sorting =
		in_array($sorting, array_keys(_sorting)) ?
			$sorting :
			array_keys(_sorting)[0];

	// ASC/DESC
	$sorting_type =
		in_array($sorting_type, _sorting_types) ?
			$sorting_type :
			_sorting_types[0];



	// Защита от тех, кто пытается limit -99999,50 сделать
	if ($limit >= 0) {

		$items =
			$mysqli->query("
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
					g.{$sorting} {$sorting_type}
					
				LIMIT 
					{$limit}, {$perPage}
			  
			")->fetch_all(MYSQLI_ASSOC);


		$total_count =
			$mysqli->query('select FOUND_ROWS()')->fetch_row()[0];

		$more =
			$total_count > $limit + $perPage;

	}

	return [
		'sorting' => $sorting,
		'sorting_type' => $sorting_type,
		'items' => $items,
		'more' =>  $more
	];
}

