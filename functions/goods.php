<?php

/**
 * Возвращает HTML блока товаров
 * @param array $good набор параметров
 * @return string
 */
function getGoodBlock($good) {
	return <<<HTML
	
		<div class="good_item">
			<div class="photo">
				<img src="{$good['PhotoURL']}">
			</div>
			<div class="name"><a href="/show.php?id={$good['ID']}" target="_blank">{$good['Name']}</a></div>
			<div class="price">{$good['Price']}₽</div>
		</div>

HTML;

}