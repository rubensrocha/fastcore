<?php
######################################
# Скрипт FastCore
# Мерчант FREE-KASSA
# Автор Jumast & Kolyaka105
######################################

# Старт сессии
session_start();

# Константа для Include
define('FastCore', true);

# Подгрузка классов системы
spl_autoload_register(function ($lfc) {
    require 'core/' . $lfc . '.php';
});

# Класс конфига
$config = new config;

# Функции
$func = new func;

$merchant_id = $config->fk_id;
$merchant_secret = $config->fk_key2;

$sign = md5($merchant_id . ':' . $_REQUEST['AMOUNT'] . ':' . $merchant_secret . ':' . $_REQUEST['MERCHANT_ORDER_ID']);

if ($sign != $_REQUEST['SIGN']) {
    die('wrong sign');
}

$m_orderid = $_REQUEST['MERCHANT_ORDER_ID'];

$id = (int)$m_orderid;
$num = $db->query("SELECT * FROM `db_insert` WHERE `id` = '$id'")->numRows();

if ($num == 0) {
    echo $_POST['MERCHANT_ORDER_ID'] . '|error';
    exit();
}

$data = $db->query("SELECT * FROM `db_insert` WHERE `id` = '$id'")->fetchArray();

if ($data['status'] == 1) {
    exit($_POST['MERCHANT_ORDER_ID'] . '|success');
}
if ($data['sum'] != $_POST['AMOUNT']) {
    exit($_POST['MERCHANT_ORDER_ID'] . '|error');
}

$uid = $data['uid'];
$sum = $data['sum'];
$time = time();

# Начисление с бонусом
$bonx = $db->query("SELECT * FROM `db_percent` WHERE `type` = '1' ORDER BY `sum_a` BETWEEN {$sum} AND {$sum}
OR {$sum} BETWEEN `sum_a` AND `sum_b`")->fetchArray();

$bonus = $bonx['sum_x'];
$sum_x = ($sum + ($sum * $bonus));

# Формируем реферер
$us_data = $db->query("SELECT rid FROM db_users WHERE id = '$uid' LIMIT 1")->fetchArray();
$rid = $us_data['rid'];
$income = ($sum * 0.1);

# Обновляем реферера
$db->query("UPDATE `db_users` SET `money_p` = `money_p` + $income, `income` = `income` + '$income' WHERE `id` = '$rid'");

# Обновляем пользователя
$db->query("UPDATE `db_users` SET `sum_in` = `sum_in` + '$sum', `ref_to` = `ref_to` + '$income', `money_b` = `money_b` + '$sum_x' WHERE `id` = '$uid'");

# Пишем в статистику
$db->query("UPDATE `db_insert` SET `status` = '1',  `sum_x` = '$sum_x',  `end` = '$time'  WHERE `id` = '$id'");
$db->query("UPDATE `db_stats` SET `inserts` = `inserts` + '$sum' WHERE `id` = '1'");

echo $m_orderid . "|success"; // Успешная оплата
exit;