<?php

require '../config.php';


$act = varStr('act');
$goods_itemID = varInt('id');
$error = $success = '';

switch ($act) {
	case 'add': {

		$goods_itemInfo = [];

		// Сохранена форма
		if ($_POST) {

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

						$mysqli = getMysqli();

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


					} else {
						$error = 'Некорректная ссылка на фотографию';
					}


				} else {
					$error = 'Некорректная цена';
				}

			} else {
				$error = 'Заполнены не все поля';
			}

		}

		$title = 'Добавление товара';
		$goods_itemInfo['error'] = $error;
		$content = getTemplate('goods_manage', $goods_itemInfo);
		break;

	}
	case 'edit': {

		if ($goods_itemID) {
			$mysqli = getMysqli();

			$goods_itemInfo = $mysqli->query("
				select
						g.ID,
						g.Name,
						g.Description,
						g.PhotoURL,
						g.Price
						
					from goods g 
				
				WHERE 
					g.ID='{$goods_itemID}'
			
			")->fetch_assoc();

			// Сохранена форма
			if ($_POST) {

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

						} else {
							$error = 'Некорректная ссылка на фотографию';
						}

					} else {
						$error = 'Некорректная цена';
					}

				} else {
					$error = 'Заполнены не все поля';
				}

			}


			if ($goods_itemInfo) {

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

			$mysqli = getMysqli();
			$mysqli->query("delete from goods WHERE ID='{$goods_itemID}'");

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