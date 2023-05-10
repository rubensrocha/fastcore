<?php if(!defined('FastCore')){exit('Opss!');}
$type=3;
if(!empty($pg->segment[2] ==='add')) $type=1; // пополнения
if(!empty($pg->segment[2] ==='edit')) $type=2; // выплаты
?>
<h3>Персонажи</h3>
<div class="btn-group mb-2 text-uppercase">
<a class="btn btn-outline-dark" href="/<?=$adm;?>/pers">Список персонажей</a>
<a class="btn btn-success" href="/<?=$adm;?>/pers/add">Добавить</a>
</div>

<?PHP
# Покупка нового завода


if(isset($_POST["delete"])){
$del_pers = intval($_POST["delete"]);
$db->Query("DELETE FROM db_tarif WHERE id = '$del_pers'");
	echo "<div class='alert alert-success text-center'>Вы успешно удалили персонажа с игры!</div>";
}
?>


<?php
if($type==1) { 
?>
<?PHP
# Добавление персонажа
if(isset($_POST['add'])){
			$title = htmlspecialchars($_POST['title']);
			$speed = htmlspecialchars($_POST['speed']);
			$price = htmlspecialchars($_POST['price']);
			$img = htmlspecialchars($_POST['img']);
			$period = htmlspecialchars($_POST['period']);
				# Добавляем персонажа и списываем деньги
				$db->Query("INSERT INTO db_tarif (title, speed, price, img, period) VALUES ('$title', '$speed', '$price', '$img', '$period')");
				echo "<div class='alert alert-success text-center'>Вы успешно добавили нового персонажа {$title} в игру и теперь оно доступно для покупки!</div>";
}
?>

<div class="row mb-3">
<div class="col-lg-6">
<div class="card">
<h3 class="card-header">Добавление персонажа</h3>
<form action="" method="post" class="card-body">
	<input type="text" name="title" placeholder="Название" class="form-control mb-2"/>
	<input type="text" name="speed" placeholder="Доходность в час(РУБ)" class="form-control mb-2"/>
	<input type="text" name="price" placeholder="Цена" class="form-control mb-2"/>
	<input type="text" name="period" placeholder="Срок тарифа" class="form-control mb-2"/>
	<input type="text" name="img" placeholder="Картинка пример: число-1 /items/1.png" class="form-control mb-2"/>
<button class="btn btn-success" name="add" type="submit">ДОБАВИТЬ</button>
 </form>
 </div>
</div>
</div>


<?
} else if($type==2) {
?>

<?
# Редактирование
if(isset($pg->segment[2])){
$idr = intval($pg->params[1]);
$db->Query("SELECT * FROM db_tarif WHERE id = '$idr' LIMIT 1");
if($db->NumRows() != 1){ echo '<div class="alert alert-danger">Персонаж с таким ID не найдена</div>'; return;}
	if(isset($_POST['title'])){
	$title = htmlspecialchars($_POST['title']);
	$title = (strlen($title) > 0) ? $title : 'Без заголовка';

	$speed = $_POST['speed'];
	$price = $_POST['price'];
	$period = $_POST['period'];
	$db->Query("UPDATE db_tarif SET title = '$title', speed = '$speed', price = '$price', period = '$period' WHERE id = '$idr'");
	$db->Query("SELECT * FROM db_tarif WHERE id = '$idr' LIMIT 1");
	 echo '<div class="alert alert-success">Персонаж отредактирован</div>';
	}
$pers = $db->fetchArray();
?>
<div class="card mb-3 w-50">
<div class="card-header">Персонаж: <b>LVL - <?=$pers['id']; ?></b></div>
<div class="card-body">
<form action="" method="post"class="m-0">
<b>Название персонажа:</b>
<input type="text" name="title" class="form-control mb-2" placeholder="Например: Уровень-<?=$pers['id']; ?>" value="<?=$pers['title']; ?>"/>
<b>Доходность в час (РУБ.):</b>
<input name="speed" class="form-control mb-2" placeholder="Например: 0.05" value="<?=$pers['speed']; ?>"/>
<b>Цена РУБ:</b>
<input name="price" class="form-control mb-2" placeholder="Например: 100" value="<?=$pers['price']; ?>"/>
<b>Срок:</b>
<input name="period" class="form-control mb-2" placeholder="Например: 100" value="<?=$pers['period']; ?>"/>
<input type="submit" class="btn btn-success mb-2" value="Сохранить" />
</form>
</div></div>
<?PHP
return;
}
?>
<?php
}
?>
<table class="table table-bordered table-striped text-center bg-white">
<thead>
<tr>
	<th>#</th>
	<th>Название</th>
	<th>Доход</th>
	<th>Цена</th>
	<th>Срок</th>
	<th>Картинка</th>
	<th colspan="2">Действие</th>
</tr>
</thead>
<tbody>


<?php

$rows = $db->query("SELECT * FROM `db_tarif` WHERE `id` > 0")->numRows();
if($rows == 0) { 
	echo '<tr><td colspan="3"><div class="alert alert-danger">Персонажей нет</div></td></tr>';
}
else {
$shop = $db->query("SELECT * FROM `db_tarif` ORDER BY `id` DESC LIMIT 50")->fetchAll();
foreach ($shop as $shop) { 
?>
<tr>
	<td><?=$shop['id']; ?></td>
	<td><?=$shop['title']; ?></td>
	<td><?=$shop['speed']; ?></td>
	<td><?=$shop['price']; ?></td>
	<td><?=$shop['period']; ?></td>
	<td><img src="/img/items/<?=$shop['img']; ?>.png" style="width:10%;"></td>
	<td><a class="btn btn-success btn-sm" href="/<?=$adm;?>/pers/edit/<?=$shop['id']; ?>">Редактировать</a></td>
	<td>
	<form action="" method="post" class="m-0 p-0">
		<input type="hidden" name="delete" value="<?=$shop['id'];?>" />
		<button class="btn btn-danger btn-sm" type="submit">Удалить</button>
	</form>

	</td>
</tr>
<?php
	}
}
?> 
  </tbody>
</table>
