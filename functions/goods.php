<?php

/**
 * Возвращает список товаров
 * @param int $page страница. от 1
 * @param string $sorting имя столбца для сортировки
 * @param string $sorting_type ASC/DESC
 * @return array
 */
function getGoods($page = 1, $sorting = '', $sorting_type = 'ASC')
{

	global $mysqli;

	$more = 0;
	$perPage = 50;
	$items = [];
	$limit = ($page - 1) * $perPage;

	$allowedSorting = array_keys(_sorting);
	$key = array_search($sorting, $allowedSorting);
	$sorting = $allowedSorting[$key];
	$sorting_type = ($sorting_type == 'DESC') ? 'DESC' : 'ASC';


	// Защита от тех, кто пытается limit -99999,50 сделать
	if ($limit >= 0) {

		$limit2 = $perPage + 1;

		$items =
			$mysqli->query("
				select
						g.ID,
						g.Name,
						g.Description,
						g.PhotoURL,
						g.Price
					from goods g 
					inner join (
					
						select
								g.ID
							from goods g
							
						WHERE
							g.Deleted=0
							
						ORDER BY 
							g.{$sorting} {$sorting_type}
							
						LIMIT 
							{$limit}, {$limit2}
							
					) g2 on g.ID=g2.ID	
			  
			")->fetch_all(MYSQLI_ASSOC);

		$more = isset($items[$perPage]);
		$items = array_slice($items, 0, $perPage);

	}

	return [
		'sorting' => $sorting,
		'sorting_type' => $sorting_type,
		'items' => $items,
		'more' => $more
	];
}

function getPrice($float) {

	$price_parts = explode('.', $float);

	$price = number_format($price_parts[0], 0, '.', ' ');

	if (intval($price_parts[1]))
		$price .= '.' . $price_parts[1];

	return $price;
}


function getSortingList($sorting, $sorting_type) {

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

	return $sortingList;
}