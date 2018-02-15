<?php

require '../config.php';

$mysqli = getMysqli();

$goods = $mysqli->query("
	select
			g.ID,
			g.Name,
			g.Description,
			g.PhotoURL,
			g.Price
		from goods g
		
	WHERE
		1
		
	ORDER BY 
		g.ID
		
	LIMIT 
		0, 50
  
");

$content = '<div class="container">';

while($good = $goods->fetch_assoc()) {
	$content .= getGoodBlock($good);
}

$content .= '</div>';


echo getTemplate('base', [
	'title' => 'Список товаров',
	'content' => $content
]);