<?php

require '../config.php';


$act = varStr('act');
$goodID = varInt('id');
$error = '';

switch ($act) {
	case 'add': {

		$goodInfo = [];

		// Сохранена форма
		if ($_POST) {

			$goodInfo['Name'] = varStr('name');
			$goodInfo['Description'] = varStr('description');
			$goodInfo['Price'] = floatval(varStr('price'));
			$goodInfo['PhotoURL'] = varStr('photo');

			if ($goodInfo['Name'] && $goodInfo['Description'] && $goodInfo['Price'] && $goodInfo['PhotoURL']) {

				$mysqli = getMysqli();

				$mysqli->query("
						insert into
								goods
							set
								Name='{$goodInfo['Name']}',
								Description='{$goodInfo['Description']}',
								Price='{$goodInfo['Price']}',
								PhotoURL='{$goodInfo['PhotoURL']}'
					");

				$goodID = $mysqli->insert_id;
				header('Location: /manage.php?act=edit&id=' . $goodID);
				die();

			} else {
				$error = 'Заполнены не все поля';
			}

		}

		$title = 'Добавление товара';
		$goodInfo['error'] = $error;
		$content = getTemplate('goods_manage', $goodInfo);
		break;

	}
	case 'edit': {

		if ($goodID) {
			$mysqli = getMysqli();

			$goodInfo = $mysqli->query("
				select
						g.ID,
						g.Name,
						g.Description,
						g.PhotoURL,
						g.Price
						
					from goods g 
				
				WHERE 
					g.ID='{$goodID}'
			
			")->fetch_assoc();

			// Сохранена форма
			if ($_POST) {

				$goodInfo['Name'] = varStr('name');
				$goodInfo['Description'] = varStr('description');
				$goodInfo['Price'] = floatval(varStr('price'));
				$goodInfo['PhotoURL'] = varStr('photo');

				if ($goodInfo['Name'] && $goodInfo['Description'] && $goodInfo['Price'] && $goodInfo['PhotoURL']) {

					$mysqli->query("
						update
								goods
							set
								Name='{$goodInfo['Name']}',
								Description='{$goodInfo['Description']}',
								Price='{$goodInfo['Price']}',
								PhotoURL='{$goodInfo['PhotoURL']}'
						WHERE 
							ID='{$goodID}'
					");

				} else {
					$error = 'Заполнены не все поля';
				}

			}


			if ($goodInfo) {

				$title = 'Редактирование товара';
				$goodInfo['error'] = $error;
				$content = getTemplate('goods_manage', $goodInfo);

			}

		}
		break;

	}
	case 'delete': {



		if ($goodID) {

			$mysqli = getMysqli();
			$mysqli->query("delete from goods WHERE ID='{$goodID}'");

		}

		header('Location: /');
		die();


	}
}

if ($content) {

	echo getTemplate('base', [
		'title' => $title,
		'pageTitle' => $title,
		'content' => $content
	]);


	echo '<div id="page_load_time">' . round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 5) . '</div>';

} else {
	echo '404';
}