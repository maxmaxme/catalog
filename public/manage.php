<?php

require '../config.php';


$act = varStr('act');
$goodID = varInt('id');
$errors = [];

switch ($act) {
	case 'add': {

		$title = 'Добавление товара';
		$content = getTemplate('goods_manage', []);
		break;

	}
	case 'edit': {

		if ($goodID) {
			$mysqli = getMysqli();

			// Сохранена форма
			if ($_POST) {

				$name = varStr('name');
				$description = varStr('description');
				$price = floatval(varStr('price'));
				$photo = varStr('photo');

				if ($name && $description && $price && $photo) {

					$mysqli->query("
						update
								goods
							set
								Name='{$name}',
								Description='{$description}',
								Price='{$price}',
								PhotoURL='{$photo}'
						WHERE 
							ID='{$goodID}'
					");

				} else {
					$errors[] = 'Заполнены не все поля';
				}

			}



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


			if ($goodInfo) {

				$title = 'Редактирование товара';
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