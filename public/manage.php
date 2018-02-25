<?php

require_once '../config.php';

$content = '';

/**
 * Проверяем поля на валидность
 * @return array result Если ок — возвращаем экранированные данные. Если не ок — исходный массив
 */
$isValidParams = function () {

	$goods_itemInfo = [];

	$goods_itemInfo['Name'] = varStr('name');
	$goods_itemInfo['Description'] = varStr('description');
	$goods_itemInfo['Price'] = number_format(varFloat('price'), 2, '.', '');
	$goods_itemInfo['PhotoURL'] = varStr('photo');

	if ($goods_itemInfo['Name'] &&
		$goods_itemInfo['Description'] &&
		$goods_itemInfo['Price'] &&
		$goods_itemInfo['PhotoURL']) {


		if (preg_match('/^[0-9]{1,8}(?:\.[0-9]{0,2})?$/', $goods_itemInfo['Price'])) {

			if (filter_var($goods_itemInfo['PhotoURL'], FILTER_VALIDATE_URL)) {

				global $mysqli;

				foreach ($goods_itemInfo as &$param)
					$param = mysqli_real_escape_string($mysqli, $param);


			} else {
				$goods_itemInfo['error'] = 'Некорректная ссылка на фотографию';
			}

		} else {
			$goods_itemInfo['error'] = 'Некорректная цена';
		}

	} else {
		$goods_itemInfo['error'] = 'Заполнены не все поля';
	}

	return $goods_itemInfo;
};


$act = varStr('act');
$goods_itemID = varInt('id');
$success = '';

switch ($act) {
	case 'add': {

		$goods_itemInfo = [];

		// Сохранена форма
		if ($_POST) {

			$goods_itemInfo = $isValidParams();

			if (!isset($goods_itemInfo['error'])) {

				$mysqli->query("
					insert into
							goods
						set
							Name='{$goods_itemInfo['Name']}',
							Description='{$goods_itemInfo['Description']}',
							Price='{$goods_itemInfo['Price']}',
							PhotoURL='{$goods_itemInfo['PhotoURL']}'
				");

				if(!$mysqli->errno) {

					$goods_itemID = $mysqli->insert_id;
					header('Location: /view.php?id=' . $goods_itemID);
					die();

				} else {
					$goods_itemInfo['error'] = 'Неизвестная ошибка';
				}

			}

		}

		$title = 'Добавление товара';
		$content = getTemplate('goods_manage', $goods_itemInfo);

		break;

	}
	case 'edit': {

		if ($goods_itemID) {

			// Сохранена форма
			if ($_POST) {

				$goods_itemInfo = $isValidParams();

				if (!isset($goods_itemInfo['error'])) {

					$mysqli->query("
						update
								goods
							set
								Name='{$goods_itemInfo['Name']}',
								Description='{$goods_itemInfo['Description']}',
								Price='{$goods_itemInfo['Price']}',
								PhotoURL='{$goods_itemInfo['PhotoURL']}'
						WHERE 
							ID='{$goods_itemID}'
					");

					if(!$mysqli->errno) {

						$success = 'Сохранено';

					} else {
						$goods_itemInfo['error'] = 'Неизвестная ошибка';
					}


				}

			}

			if (!$_POST || $success) {

				$goods_itemInfo = $mysqli->query("
					select
							g.ID,
							g.Name,
							g.Description,
							g.PhotoURL,
							g.Price
							
						from goods g 
					
					WHERE 
						g.ID='{$goods_itemID}' AND 
						g.Deleted=0
				
				")->fetch_assoc();


			}



			if ($goods_itemInfo) {

				$title = 'Редактирование товара';
				$goods_itemInfo['success'] = $success;
				$content = getTemplate('goods_manage', $goods_itemInfo);

			}

		}
		break;

	}
	case 'delete': {

		if ($goods_itemID) {

			$mysqli->query("update goods set Deleted=1 WHERE ID='{$goods_itemID}'");

		}

		header('Location: /');
		die();


	}
}

if ($content) {

	$content .= getPageLoadTime();

	echo getTemplate('base', [
		'title' => $title,
		'pageTitle' => $title,
		'content' => $content
	]);


} else {
	echo getErrorPage(404);
}