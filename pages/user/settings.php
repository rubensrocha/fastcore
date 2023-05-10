<?php if(!defined('FastCore')){exit('Opss!');}

# Заголовок
$opt['title'] = 'Настройки';
?>

<div class="row">
<div class="col-lg-6">
<div class="card">
<div class="card-header">Поменять пароль</div>
<div class="p-3">
<?
if(isset($_POST['new_pass'])){
$pass = $func->FPass($_POST['pass']);
$pass_new = $func->FPass($_POST['pass_new']);
if($pass !== false AND strtolower($pass) == strtolower($user['pass'])){
	if($pass_new !== false){
		$db->query("UPDATE db_users SET pass = '$pass_new' WHERE id = '$uid'");        
		echo '<div class="alert alert-success">Пароль успешно изменен!</div>';
	}else echo '<div class="alert alert-warning">Новый пароль имеет неверный формат!</div>';
}else echo '<div class="alert alert-danger">Старый пароль заполнен неверно!</div>';
}
?>

<form action="" method="POST">
<input type="password" class="form-control" name="pass" placeholder="Старый пароль">
<input type="password" class="form-control mt-2" name="pass_new" placeholder="Новый пароль">
<center>
<button class="btn btn-success mt-2" name="new_pass" type="submit">СМЕНИТЬ ПАРОЛЬ</button>
</center>
</form>
</div>
</div>
</div>

<div class="col-lg-6">
<div class="card">
<div class="card-header">Платежные реквизиты</div>
<div class="p-3">
<?php
# Класс кошельков
$wallet = new wallets();

# Кошельки и платежный пароль
$ps = $db->query('SELECT * FROM db_purse WHERE id = ?',$uid)->fetchArray();

# Привязать кошелек
if(isset($_POST['save_wallet'])) {

$payeer = $wallet->payeer_wallet($_POST['payeer']);
$yandex = $wallet->yandex_wallet($_POST['yandex']);
$qiwi = $wallet->qiwi_wallet($_POST['qiwi']);

# PAYEER
if($payeer !== false) {
$ok = $db->query('SELECT * FROM db_purse WHERE payeer = ?',array($payeer))->numRows();
	if ($ok == 0 && $ps['payeer'] == '0'){
		$db->query('UPDATE db_purse SET payeer = ? WHERE id = ?',array($payeer, $uid));
		$save_p[]='Кошелек PAYEER сохранен';
	} else { $err[] = 'Такой кошелек занят!'; }
}

# QIWI
if($qiwi !== false) {
$ok = $db->query('SELECT * FROM db_purse WHERE qiwi = ?',array($qiwi))->numRows();
	if ($ok == 0 && $ps['qiwi'] == '0'){
		$db->query('UPDATE db_purse SET qiwi = ? WHERE id = ?',array($qiwi, $uid));
		$save_p[]='Кошелек QIWI сохранен';
	} else { $err[] = 'Такой кошелек занят!'; }
}

# YANDEX
if($yandex !== false) {
$ok = $db->query('SELECT * FROM db_purse WHERE yandex = ?',array($yandex))->numRows();
	if ($ok == 0 && $ps['yandex'] == '0'){
		$db->query('UPDATE db_purse SET yandex = ? WHERE id = ?',array($yandex, $uid));
		$save_p[]='Кошелек YANDEX сохранен';
	} else { $err[] = 'Такой кошелек занят!'; }
}

# Errors
if (!empty($err)) {
	echo '<div class="alert alert-danger">'.array_shift($err).'</div>';
}

# Success
if (!empty($save_p)) {
	echo '<div class="alert alert-success">'.array_shift($save_p).'</div>'; }
else {
	echo '<div class="alert alert-danger">Кошелек заполнен неверно!</div>'; }
}
?>

<form action="" method="POST">
<?php
if ($ps['payeer'] == '0') {
?>
<div class="input-group mb-2">
	<div class="input-group-prepend"><span class="input-group-text">PAYEER</span></div>
	<input class="form-control" type="text" name="payeer" placeholder="Пример P111111111" value=""/>
</div>
<?php
} else {
	echo '<div class="alert alert-info p-2"><b>ВАШ PAYEER:</b> '.$ps['payeer'].'</div>';
}
if ($ps['qiwi'] == '0') {
?>
<div class="input-group mb-2">
	<div class="input-group-prepend"><span class="input-group-text">QIWI</span></div>
	<input class="form-control" type="text" name="qiwi" placeholder="Пример +79201234567" value="" />
</div>
<?php
} else {
	echo '<div class="alert alert-info p-2"><b>ВАШ QIWI:</b> '.$ps['qiwi'].'</div>';
}
if ($ps['yandex'] == '0') {
?>
<div class="input-group">
	<div class="input-group-prepend"><span class="input-group-text">ЯНДЕКС</span></div>
	<input class="form-control" type="text" name="yandex" placeholder="Пример 40012345600000" value="" />
</div>
<?php
} else {
	echo '<div class="alert alert-info p-2"><b>ВАШ ЯНДЕКС:</b> '.$ps['yandex'].'</div>';
}
?>
<?php
If ($ps['payeer'] && $ps['qiwi'] && $ps['yandex'] > 0) { } else {
?>
<center>
<button class="btn btn-success mt-2" name="save_wallet" type="submit">Сохранить реквизиты</button>
</center>
<? } ?>

</form>
</div>

</div>
</div>
</div>
