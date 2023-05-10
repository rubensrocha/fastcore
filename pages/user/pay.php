<?php if(!defined('FastCore')){exit('Oops!');}

$opt['title'] = 'Заказать выплату';

# Конфигурация
$db->query("SELECT * FROM db_conf WHERE id = '1' LIMIT 1");
$cnf = $db->fetchArray();

# Кошельки и платежный пароль
$ps = $db->query('SELECT * FROM db_purse WHERE id = ?',$uid)->fetchArray();

# Параметры лимитов
$maxPay = 2500;
$todayLimit = 24;
$accPay = $cnf['acc_pay'];

# Способ заказа выплаты
$py = $pg->segment[2] ?? NULL;

if($py == 'payeer') {
$valid = $ps['payeer'];
$varPy = 'P1234567890';
$pSys = '1136053';
$minPay = 1;
}

if($py == 'qiwi') {
$valid = $ps['qiwi'];
$varPy = '+79012345679';
$pSys = '26808';
$minPay = 10;
}

if($py == 'yandex') {
$valid = $ps['yandex'];
$varPy = '41001234500000';
$pSys = '57378077';
$minPay = 10;
}

# Выбран способ оплаты
if ($py) {
$pyArr= array('payeer' => 'PAYEER', 'qiwi' => 'QIWI', 'yandex' => 'YANDEX');
$pyName = $pyArr[$pg->segment[2]] ?? FALSE;

$opt['title'] = 'Вывод средств '.$pyName.'';

# Фильтрация кошельков
$wallet = new wallets();

# Ищем последнюю выплату
$payments = $db->query("SELECT * FROM db_payout WHERE uid = '$uid' AND `add` > '$time'")->fetchArray();

?>

<center>
<?
# Заносим выплату
if(isset($_POST['pay'])) {
$pWallet = $py.'_wallet';
$purse = $wallet->$pWallet($_POST['purse']);

$sum = filter_var($_POST['sum'], FILTER_VALIDATE_FLOAT);
$com = $sum - ($sum * 0.00); // коммисия 0%

if($valid != false){
if($purse == $valid) {

if ($payments['add'] <= time() - $todayLimit * 3600) {
if($sum <= $maxPay) {
if($sum >= $minPay) {
if($user['sum_in'] >= $accPay) {
if($sum <= $user['money_p']) {

# Делаем выплату
$payeer = new rfs_payeer($config->py_NUM, $config->py_apiID, $config->py_apiKEY);
if ($payeer->isAuth()) {

$arBalance = $payeer->getBalance();
if($arBalance["auth_error"] == 0) {

$balance = $arBalance["balance"]["RUB"]["DOSTUPNO"];

if($balance >= $sum) {
$array = array(
	'action' => 'output',
	'ps' => $pSys,
	'curIn' => 'RUB', // счет списания
	'sumOut' => $com, // сумма получения
	'curOut' => 'RUB', // валюта получения
	'param_ACCOUNT_NUMBER' => $purse // получатель
);
$initOutput = $payeer->initOutput($array);
	if ($initOutput){
	$historyId = $payeer->output();
		if (!empty($historyId)) {

# Снимаем с пользователя
$db->query("UPDATE db_users SET money_p = money_p - '$sum' WHERE id = '$uid'");

# Вставляем запись в выплаты
$da = time();
$dd = $da + 60*60*24*15;
$ppid = $historyId;
$db->query("INSERT INTO db_payout (uid, login, purse, sum, sys, `add`, `del`, status) VALUES ('$uid','$login','$purse','$sum','$ppid','$da','$dd','3')");
$db->query("UPDATE db_users SET sum_out = sum_out + '$sum' WHERE id = '$uid'");

# Пишет в статистику
$db->query("UPDATE db_stats SET payments = payments + '$sum' WHERE id = '1'");
	echo '<div class="alert alert-success">Деньги успешно переведены на ваш кошелек!</div>';
} else {
	echo "<div class='alert alert-danger text-center'>Внутреняя ошибка - сообщите о ней администратору!</div>";	
}
	}else{
	echo '<div class="alert alert-danger">Ошибка ['.print_r($payeer->getErrors(), true).'] - попробуйте через 20-30 секунд или сообщите о ней администратору!</div>'; 
}
	}else echo '<div class="alert alert-danger">Ошибка 629. Сообщите о ней администратору!</div>';							
	}else echo '<div class="alert alert-danger">Ошибка 630. Не удалось выплатить! Попробуйте позже</div>';	
	}else echo '<div class="alert alert-danger">Ошибка 631. Не удалось выплатить! Попробуйте позже</div>';							
	}else echo '<div class="alert alert-warning">Вы указали больше, чем имеется на вашем счету</div>';
	}else echo '<div class="alert alert-danger">Пополните баланс минимум на '.$accPay.' руб! После этого выплаты будут доступны.</div>';	
	}else echo '<div class="alert alert-warning">Минимальная сумма для выплаты составляет '.$minPay.' руб!</div>';
	}else echo '<div class="alert alert-warning">Максимальная сумма для выплаты составляет '.$maxPay.' руб!</div>';
	}else echo '<div class="alert alert-danger">Вы уже заказывали выплату за последние '.$todayLimit.' час(ов).</div>';
	}else echo '<div class="alert alert-warning">Номер счета '.$purse.' указан неверно</div>';
	}else echo '<div class="alert alert-warning">Сохраните кошелек '.$pyName.' в настройках.</div>';
}
?>
</center>


<div class="row">

<div class="col-xl-4">
<div class="card">
<div class="card-header"><b>ВЫПЛАТА НА <?=$pyName;?></b><br/>
<small>Обработка выплат автоматический. Минимум <?=$minPay;?> руб.</small>
</div>
<div><form action="" method="POST"><div class="p-3">
<label>Ваш кошелек</label>
<div class="input-group">
	<div class="input-group-prepend"><span class="input-group-text"><?=$pyName;?></span></div>
	<input class="form-control" type="text" placeholder="Пример: <?=$varPy;?>" value="<?=(!empty($valid) > 0) ? $valid : FALSE;?>" name="purse">
</div></div>
<hr class="my-0">
<div class="p-3"><label>Сумма выплаты</label>
<div class="input-group">
<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-ruble-sign"></i></span></div>
	<input class="form-control" placeholder="Сумма выплаты" name="sum" value="<?=round($user['money_p'],2); ?>">
<div class="input-group-append"><button class="btn btn-success" name="pay" type="submit">Вывести</button></div>
</div>
</div></form></div>
</div>
</div>

<div class="col-xl-8">
<div class="card">
<div class="card-header"><b>Последние 20 выплат</b></div>
<div class="pb-0">
<table class="table table-bordered table-sm table-striped text-center">
<thead>
	<th>ID</th>
	<th>Сумма</th>
	<th>Кошелек</th>
	<th>Дата</th>
	<th>Статус</th>
</thead>
<?php
# Статусы
$status_array = array(0 => "Проверяется", 1 => "Выплачивается", 2 => "Отменена", 3 => "Выплачено");

$pays = $db->query("SELECT * FROM db_payout WHERE uid = '$uid' ORDER BY id DESC LIMIT 20")->fetchAll();
	foreach($pays as $pay){
?>
<tr>
	<td><?=$pay['id']; ?></td>
	<td><?=sprintf("%.2f",$pay['sum']); ?></td>
	<td><?=$pay['purse']; ?></td>
	<td><?=date("d.m.Y в H:i",$pay['add']); ?></td>
	<td><?=$status_array[$pay['status']]; ?></td>
</tr>
<?php
	}
?>
</table>
</div>
</div>
</div>
</div>
<?php
return;
}
?>
<div class="card mb-2">
<h5 class="card-header p-2 text-uppercase text-center">Выберите платежную систему для вывода средств</h5>
<div class="row m-1">
<div class="col-md-4 p-1">
	<a href="/user/pay/payeer" class="card p-5 bg-light mb-0" style="background: url(/img/pay/ps/1.png) no-repeat center center;background-size: 120px;"><br/><br/><br/></a>
</div>
<div class="col-md-4 p-1">
	<a href="/user/pay/qiwi" class="card p-5 bg-light mb-0" style="background: url(/img/pay/ps/2.png) no-repeat center center;background-size: 120px;"><br/><br/><br/></a>
</div>
<div class="col-md-4 p-1">
	<a href="/user/pay/yandex" class="card p-5 bg-light mb-0" style="background: url(/img/pay/ps/3.png) no-repeat center center;background-size: 120px;"><br/><br/><br/></a>
</div>
</div>
</div>