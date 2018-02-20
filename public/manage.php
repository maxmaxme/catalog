<?php

require_once '../config.php';


function isValidParams($goods_itemInfo = []) {

	$goods_itemInfo['Name'] = varStr('name');
	$goods_itemInfo['Description'] = varStr('description');
	$goods_itemInfo['Price'] = round(varFloat('price'), 2);
	$goods_itemInfo['PhotoURL'] = varStr('photo');


	if ($goods_itemInfo['Name'] &&
		$goods_itemInfo['Description'] &&
		$goods_itemInfo['Price'] &&
		$goods_itemInfo['PhotoURL']) {

		if (preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $goods_itemInfo['Price'])) {

			if (filter_var($goods_itemInfo['PhotoURL'], FILTER_VALIDATE_URL)) {

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
}


$act = varStr('act');
$goods_itemID = varInt('id');
$error = $success = '';

switch ($act) {
	case 'add': {

		$goods_itemInfo = [];

		// Сохранена форма
		if ($_POST) {

			$goods_itemInfo = isValidParams();

			if (!$error = $goods_itemInfo['error']) {

				$mysqli->query("
					insert into
							goods
						set
							Name='{$goods_itemInfo['Name']}',
							Description='{$goods_itemInfo['Description']}',
							Price='{$goods_itemInfo['Price']}',
							PhotoURL='{$goods_itemInfo['PhotoURL']}'
				");


				$goods_itemID = $mysqli->insert_id;
				header('Location: /manage.php?act=edit&id=' . $goods_itemID);
				die();

			}

		}

		$title = 'Добавление товара';
		$goods_itemInfo['error'] = $error;
		$content = getTemplate('goods_manage', $goods_itemInfo);
		break;

	}
	case 'edit': {

		if ($goods_itemID) {

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

			// Сохранена форма
			if ($_POST) {

				$goods_itemInfo = isValidParams($goods_itemInfo);

				if (!$error = $goods_itemInfo['error']) {

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

					$success = 'Сохранено';

				}

			}


			if ($goods_itemInfo) {

				$goods_itemInfo['Price'] = number_format($goods_itemInfo['Price'], 2, '.', '');

				$title = 'Редактирование товара';
				$goods_itemInfo['error'] = $error;
				$goods_itemInfo['success'] = $success;
				$goods_itemInfo['DeleteButton'] = 'Удалить';
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

	echo '<a href="/" class="btn btn-info">К списку</a>';


	echo getTemplate('base', [
		'title' => $title,
		'pageTitle' => $title,
		'content' => $content
	]);


	echo getPageLoadTime();

} else {
	echo '404';
}