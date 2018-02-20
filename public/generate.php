<?php
require '../config.php';


$names = [
	[
		'Супер',
		'Новый',
		'Потрясающий',
		'Великолепный',
		'Невероятный',
	],
	[
		'фен',
		'телевизор',
		'телефон',
		'ноутбук',
		'пылесос',
		'холодильник',
		'блендер',
		'утюг',
	],
	[
		'Филиппс',
		'Панасоник',
		'Самсунг',
		'Сони',
		'Эппл',
		'Сяоми',
		'Ксяоми',
		'Салями',
	],
	[
		100,
		200,
		300,
		400,
		500,
		600,
		700,
		9000
	]
];


for ($i = 1; $i <= 10000; $i++) {


	$name = implode(' ', array_map(function ($type) {
		return $type[rand(0, count($type) - 1)];
	}, $names));

	$description = 'Описание для ' . $name;

	$price = rand(1, 999) * 100;

	$photo = 'https://picsum.photos/300?random';

	$sql = "insert into goods set Name='{$name}', Description='{$description}', Price='{$price}', PhotoURL='{$photo}'";

	$mysqli->query($sql);

}