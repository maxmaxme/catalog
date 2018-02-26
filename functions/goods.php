<?php

/**
 * Возвращает версию кэша
 * @param string $sorting Тип сортировки
 * @return int
 */
function getGoodsCacheKey($sorting)
{
	global $memcached;

	$key = $sorting . '_cache_version';

	$version = $memcached->get($key);

	if (!$version) {
		$version = 1;
		$memcached->set($key, $version);
	}

	return $version;

}

/**
 * Ставит версию кэша +1 тем самым обнуляя кэш
 * @param string $sorting Тип сортировки
 */
function resetGoodsCache($sorting)
{
	global $memcached;

	$key = $sorting . '_cache_version';

	$memcached->increment($key);
}


/**
 * Возвращает список товаров
 * @param int $page страница. от 1
 * @param string $sorting имя столбца для сортировки
 * @param string $sorting_type ASC/DESC
 * @return array
 */
function getGoods($page = 1, $sorting = '', $sorting_type = 'ASC')
{

	global $mysqli, $memcached;

	$more = false;
	$perPage = 50;
	$items = [];
	$offset = ($page - 1) * $perPage;

	$allowedSorting = array_keys(_sorting);
	$key = array_search($sorting, $allowedSorting);
	$sorting = $allowedSorting[$key];
	$sorting_type = ($sorting_type == 'DESC') ? 'DESC' : 'ASC';


	// Защита от тех, кто пытается limit -99999,50 сделать
	if ($offset >= 0) {

		$limit = $perPage + 1;

		$v = getGoodsCacheKey($sorting);
		$memcachedKey = "goodsIDs_{$sorting}{$v}_{$sorting_type}_{$offset}_{$limit}";

		// ищем в кэше ID товаров, которые попадают под нашу выборку
		if (!$IDs = $memcached->get($memcachedKey)) {

			$IDs =
				$mysqli->query("
					select 
							g.ID
						from goods g
						
					WHERE
						g.Deleted=0
						
					ORDER BY 
						g.{$sorting} {$sorting_type}
						
					LIMIT 
						{$offset}, {$limit}
				  
				")->fetch_all(MYSQLI_ASSOC);


			$IDs = array_column($IDs, 'ID');
			$IDs = implode(',', $IDs) ?: 0;

			$memcached->set($memcachedKey, $IDs, 24 * 60 * 60); // сутки

		}


		$items =
			$mysqli->query("
				select
						g.ID,
						g.Name,
						g.Description,
						g.PhotoURL,
						g.Price
					from goods g 
				
				WHERE g.ID IN ({$IDs})
			  
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

function getPrice($float)
{

	$price_parts = explode('.', $float);

	$price = number_format($price_parts[0], 0, '.', ' ');

	if (intval($price_parts[1]))
		$price .= '.' . $price_parts[1];

	return $price;
}


function getSortingList($sorting, $sorting_type)
{

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