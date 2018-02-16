<?php

require_once '../../config.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_REQUEST['method'];

$result = [];


switch ($method) {
	case 'getGoods': {

		$goods = getGoods(varInt('page'), varStr('sorting'), varStr('sorting_type'));

		$result = [
			'result' => $goods['items'] ? $goods : [],
			'error' => (!$goods['items'] ? 'Ничего не найдено' : '')
		];

		break;
	}
	default: {
		$result = [
			'error' => 'Unknown method'
		];
	}
}

$result['success'] = !$result['error'];

echo json_encode($result, 256);