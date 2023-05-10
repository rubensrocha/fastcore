<?php if(!defined('FastCore')){exit('Opss!');}

# Заголовок
$opt['title'] = 'Покупка';

# Персонажи
$pers = $db->query('SELECT * FROM db_tarif')->fetchAll();
?>
 <div class="alert bg-light text-center">
Оплачивайте тарифы, и получайте доход каждый час, который Вы сможете вывести на свой электронный кошелек. <br>
Приобретенные тарифы приносят доход - без перерывов и выходных, СТАБИЛЬНО.
</div>
<?php

# Покупка персонажа
if(isset($_POST['buy']) && $id = intval($_POST['buy'])) {
	foreach ($pers as $buy) {
		if($buy['id'] === $id) {

			# Проверка баланса
			if($buy['price'] <= $user['money_b']) {

			# Характеристика
			$title = $buy['title'];
			$speed = $buy['speed'];
			$price = $buy['price'];
			$time = time();
			$end = $time+60*60*24*$buy['period'];

			# Добавляем персонажа и списываем деньги
			$db->query("UPDATE db_users SET money_b = money_b - $price WHERE id = '$uid'");
			$db->query("INSERT INTO db_store (uid, tarif, title, speed, level, `add`, `end`, `last` ) VALUES ('$uid', '$id', '$title', '$speed', '1', '$time', '$end', '$time')");
			echo '<div class="alert alert-success text-center">Успешная оплата - '.$title.'!</div>';

			} else echo '<div class="alert alert-danger text-center">Недостаточно средств для оплаты!</div>';	
		}
	}
}
?>
<div class="row">

<?php
foreach($pers as $b){
$month= sprintf("%.0f",($b['speed']*100)/$b['price']*24*30);
?>

<div class="col-md-4 col-sm-6 text-center">
<div class="card mb-2">
<h5 class="card-header"><?=$b['title']; ?></h5>
<div class="p-2">
<img src="/img/items/<?=$b['img']; ?>.png" style="max-width:75%;">
<p>
Окупаемость - <b><?=$month; ?>% в мес.</b><br/>
Доход - <b><?=$b['speed']*24; ?> в день</b><br/>
Доход - <b><?=$b['speed']; ?> в час</b><br/>
Срок - <b><?=$b['period']; ?> дней</b><br/>
Цена - <b><?=$b['price']; ?> руб.</b>
</p>
<form action="" method="post" class="m-0">
<input type="hidden" name="buy" value="<?=$b['id'];?>" />
<button class="btn btn-success" type="submit">Купить</button>
</form>
</div>
</div>
</div>

<?php
	}
?> 

</div>