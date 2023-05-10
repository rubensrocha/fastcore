<?php if(!defined('FastCore')){exit('Opss!');}

# Заголовок
$opt['title'] = 'Сбор прибыли';

# Конфиг БД
$cnf = $db->query("SELECT * FROM db_conf WHERE id = '1' LIMIT 1")->fetchArray();

?>
<div class="alert bg-light text-center">
Собирайте доход который принесли Ваши персонажи.<br>
Полученный доход Вы сможете обменять на покупки или вывести на свой электронный кошелек.<br>
</div>

<?PHP

if(isset($_POST['sbor'])){
	        $id = intval($_POST['sbor']);
			if($id != -1){
			$db->Query("SELECT level, title, tarif, speed, last, `add`, `end`, id FROM db_store WHERE uid = '$uid' AND id = '$id' ORDER BY id ASC");
			}else{
			$db->Query("SELECT level, title, tarif, speed, last, `add`, `end`, id FROM db_store WHERE uid = '$uid'");
			}

			if($db->NumRows() > 0){
			$pers = $db->FetchArray();

			# Считаем выручку
			$profit = $func->SumCalc($pers['speed'], $pers['tarif'], $pers['last']);

			# Ограничиваем сбор
			if($profit > $cnf['min_s']){

			# Сравниваем время
			$time = time();
			$endlife = $pers['end'];

			# Если срок истек, отключаем сбор
			if($time <= $pers['end']){

			# Распределяем и отдаем выручку пользователю
			$money_add = $profit / $cnf['coint'];
			$money_b = ( (100 - $cnf['p_sell']) / 100) * $money_add;
			$money_p = ( ($cnf['p_sell']) / 100) * $money_add;
			
			$db->Query("UPDATE db_store SET last ='".time()."' WHERE id = '".$pers['id']."'");
			$db->Query("UPDATE db_users SET money_b = money_b + '$money_b', money_p = money_p + '$money_p' WHERE id = '$uid'");
			$name = $pers['title'];

			echo '<div class="alert alert-success">Вы собрали выручку с персонажа '.$name.' в размере '.$money_b.' руб на покупки и '.$money_p.' на вывод.</div>';
 			} else echo '<div class="alert alert-danger">Срок персонажа истек!</div>';
 		} else echo '<div class="alert alert-danger">Минимальная сумма для сбора '.$cnf['min_s'].' руб.</div>';
	}else echo '<div class="alert alert-danger">Нечего собирать! </div>';
}
?>
<?php 
$db->Query("SELECT * FROM db_store WHERE uid = '$uid' ORDER BY id ASC");
	if($db->NumRows() > 0){
?>
<div class="row">
<?php
$pers = $db->query("SELECT * FROM db_store WHERE uid = '$uid' ORDER BY end DESC")->fetchAll();
  	foreach($pers as $pers){
# Таймер
$dt=$pers['end']-time();
$dd=(int)($dt/86400);
$hh=(int)(($dt-$dd*86400)/3600);
$mm=(int)(($dt-$dd*86400-$hh*3600)/60);
$ss=(int)($dt-$dd*86400-$hh*3600-$mm*60);
?>
	<div class="col-xl-3 col-lg-4 col-md-6 text-center">
	<div class="card mb-2">
	<h6 class="card-header"><small><?=$pers['title']; ?></small></h6>
	<div class="p-2"><img src="/img/items/<?=$pers['tarif']; ?>.png" style="max-width: 50%;">
<?php
# Скрыть
$endlife2 = $pers['end'];
if(time() <= $endlife2) {
?>

<br/>
<div class="badge badge-light mb-1 mt-2" style="font-size: 110%;display: block;">
<span class="text-info"><?=$profit = $func->SumCalc($pers['speed'], $pers['tarif'], $pers['last']);?></span>
<br/>
<small>Доход:</small>
</div>


<span class="badge badge-light mb-1" style="font-size: 95%;display: block;" title="Осталось дней до завершения персонажа">
<span class="text-danger"><?=sprintf("%01dд %02d:%02d:%02d", $dd, $hh, $mm, $ss);?></span>
<br/>
<small>Срок тарифа:</small>
</span>

	<form action="" method="post" class="m-0">
	<input type="hidden" name="sbor" value="<?=$pers['id']?>">
	<input type="submit" class="btn btn-success btn-sm" value="СОБРАТЬ">
	</form>
<? } else echo '<div class="alert alert-danger mt-2 mb-0">Завершен</div>'; ?>
	</div>
  	</div>
	</div>
	<?PHP
	}
?>
</div>  

<?php
	} else echo '<div class="alert alert-danger text-center">У Вас нет персонажей, купите их!</div>';
?>
