<?php if(!defined('FastCore')){exit('Oops!');}

$opt['title'] = 'Пополнить баланс';

include('inc/akcii.php'); // Бонусы

# Способ платежа
$py = $pg->segment[2] ?? NULL;

if($py == 'payeer') {
$py_list = 'СДЕЛАЙТЕ ПЕРЕВОД ЛЮБОЙ СУММЫ НА НОМЕР <b>'.$config->py_NUM.'</b>, СКОПИРУЙТЕ ID ОПЕРАЦИИ, ВСТАВЬТЕ В ПОЛЕ СЛЕВА И НАЖМИТЕ ЗАЧИСЛИТЬ.';
$py_prc = '0';
}

if($py == 'freekassa') {
$py_list = 'FKWALLET, Яндекс.Деньги, Qiwi, Payeer, Advcash, Perfect Money, VISA,  BITCOIN, ETHEREUM, Monero, Dogecoin, DASH, LITECOIN, Steam Pay, Exmo, МТС, ТЕЛЕ2, МЕГАФОН, БИЛАЙН, Сбербанк Онлайн.';
$py_prc = '0';
}

# Выбран способ оплаты
if ($py) {

$sys_arr = array('payeer' => 'Payeer', 'freekassa' => 'FreeKassa');
$sys_py = $sys_arr[$pg->segment[2]] ?? FALSE;
$opt['title'] = 'Пополнить через '.$sys_py.'';

# Оплата через Payeer
if(!empty($_POST['txn']) && $py == 'payeer') {
    $txn = intval($_POST['txn']);
    $db->query("SELECT `id` FROM `db_insert` WHERE `operation_id` = '$txn'");
    if($db->numRows() == 0){
        $payeer = new rfs_payeer($config->py_NUM, $config->py_apiID, $config->py_apiKEY);
        if ($payeer->isAuth()){
            $data = $payeer->getHistoryInfo($txn);
            if(empty($data['auth_error']) && !empty($data['info'])){
                if($config->py_NUM == $data['info']['to']){
                    if($data['info']['status'] == 'execute'){
                        if($data['info']['type'] == 'transfer'){
                            if($data['info']['protect'] == 'N'){
                                if($data['info']['curOut'] == 'RUB'){
                                    $db->query("SELECT `id` FROM `db_insert` WHERE `operation_id` = '$txn'");
                                    if($db->numRows() == 0){
                                        $amount = $data['info']['sumOut'];
                                        # Начисление с бонусом
                                        $sum_x = 0;
                                        $bonx = $db->query("SELECT * FROM `db_percent` WHERE `type` = '1' ORDER BY `sum_a` BETWEEN {$amount} AND {$amount} OR {$amount} BETWEEN `sum_a` AND `sum_b`")->fetchArray();
                                        $bonus = $bonx['sum_x'];
                                        $sum_x = ($amount + ($amount * $bonus));
                                        $db->query("INSERT INTO `db_insert` (`uid`,`login`,`sum`,`sum_x`,`sys`,`status`,`operation_id`,`add`,`end`) VALUES ('$uid','$login','$amount','$sum_x','$sys_py','1','$txn','". strtotime($data['info']['dateCreate']) ."','". time() ."')");
                                        # Формируем реферер
                                        $us_data = $db->query("SELECT rid FROM db_users WHERE id = '$uid' LIMIT 1")->fetchArray();
                                        $rid = $us_data["rid"];
                                        $income = ($amount * 0.05);
                                        # Обновляем реферера
                                        $db->query("UPDATE `db_users` SET `money_p` = `money_p` + '$income', `income` = `income` + '$income' WHERE `id` = '$rid'");
                                        # Обновляем пользователя
                                        $db->query("UPDATE `db_users` SET `sum_in` = `sum_in` + '$amount', `ref_to` = `ref_to` + '$income', `money_b` = `money_b` + '$sum_x' WHERE `id` = '$uid'");
                                        # Пишем в статистику
                                        $db->query("UPDATE `db_stats` SET `inserts` = `inserts` + '$amount' WHERE `id` = '1'");
                                        echo '<center class="alert alert-success">Баланс успешно пополнен!</center>';
                                    }else{
                                        echo '<center class="alert alert-danger">Неверный номер операции!</center>';
                                    }
                                }else{
                                    echo '<center class="alert alert-danger">Отправляйте переводы только в RUB!</center>';
                                }
                            }else{
                                echo '<center class="alert alert-danger">Отправляйте перевод без протекции!</center>';
                            }
                        }else{
                            echo '<center class="alert alert-danger">Неверный номер операции!</center>';
                        }
                    }else{
                        echo '<center class="alert alert-danger">Неверный номер операции!</center>';
                    }
                }else{
                    echo '<center class="alert alert-danger">Неверный номер операции!</center>';
                }
            }else{
                echo '<center class="alert alert-danger">Ошибка 632! Обратитесь к администратору!</center>';
            }
        }else{
            echo '<center class="alert alert-danger">Ошибка 631! Обратитесь к администратору!</center>';
        }
    }else{
        echo '<center class="alert alert-danger">Неверный номер операции!</center>';
    }
}

# Оплата через FK
if (isset($_POST['sum']) && $py == 'freekassa') {

$sum = round(floatval($_POST["sum"]),2);
$sys = 'freekassa';
$sum_x = '0';

# Заносим в БД
$db->query("INSERT INTO db_insert (uid, login, sum, sum_x, sys, `add`, status) VALUES ('$uid','$login','$sum','$sum_x','$sys','".time()."','0')");

$order_id = $db->LastInsert();
$fk_merchant_id = $config->fk_id;
$fk_merchant_key = $config->fk_key;

# Это соль
$hash = md5($fk_merchant_id.':'.$sum.':'.$fk_merchant_key.':'.$order_id);
?>
<center>
<form method="GET" action="https://www.free-kassa.ru/merchant/cash.php">
	<input type="hidden" name="m" value="<?=$fk_merchant_id?>">
	<input type="hidden" name="oa" value="<?=$sum?>"> 
	<input type="hidden" name="s" value="<?=$hash?>">
	<input type="hidden" name="us_id" value="<?=$uid;?>">
	<input type="hidden" name="o" value="<?=$order_id;?>" />
	<input type="submit" value="Оплатить через FreeKassa" class="btn btn-lg btn-success">
</form>
</center>

<?php
	return;
}
?>

<div class="row text-center text-uppercase">
<div class="col-lg-6">
<div class="card">
<h5 class="card-header">Пополнить баланс через <b><?=$sys_py; ?></b></h5>
<div class="card-body">
<?PHP
if($py == 'payeer'){
?>
<form action="" method="post">
    <div class="input-group mb-2">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fa fa-ruble-sign"></i>
            </span>
        </div>
        <input type="text" class="form-control" name="txn" placeholder="ID операции" />
    </div>
	<input type="submit" value="Перейти к оплате" class="btn btn-lg btn-success"/>
</form>
<?PHP
}else{
?>
<script type="text/javascript">
	var cf= 1;
function generateThis() {
	var sum=document.getElementById("getsum").value;
	var mn=sum*cf;
	var pro=0;
<?php
$bbb= $db->query('SELECT * FROM db_percent WHERE type = 1  ORDER BY sum_a < sum_a DESC LIMIT 7')->fetchAll();
foreach ($bbb as $inb) { 
?>
	if(sum><?=$inb['sum_a']; ?>){ mn=sum*cf;pro=<?=$inb['sum_x']; ?>;}
<?php } ?>
	$("#d1").html(pro *100);
	$("#d2").html( (mn=sum*pro ).toFixed(2));	
	$("#d3").html( (sum*1).toFixed(2));	
}
</script>
<form action="" method="post">
<div class="input-group mb-2">
	<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-ruble-sign"></i></span></div>
	<input type="number" class="form-control" value="100" min="1" max="15000" name="sum" onkeyup="generateThis();" id="getsum" />
</div>

<div class="card bg-light mb-2">
<div class="p-2">
Получаете: <b id="d3"></b> <small>РУБ.</small>
 <span class="badge badge-warning p-1" style="font-size: 100%"><small>Бонус:</small> <b id="d2"></b> <small>РУБ.</small> (+<b id="d1"></b>%)</span><br/>
</div>
</div>
	<input type="submit" value="Перейти к оплате" class="btn btn-lg btn-success"/>
</form>
<?PHP
}
?>
</div>
</div>
</div>

<div class="col-lg-6">
<div class="card">
<h5 class="card-header">Способы оплаты:</h5>
<div class="card-body"><?=$py_list; ?><hr>
<b>Коммисия при пополнении <?=$py_prc; ?>%</b>
</div>
</div>
</div>

<script>
	var sum=document.getElementById("getsum").value;
	var pro=0.1;
	var mn=sum*pro;
	$("#d1").html(pro *100);
	$("#d2").html( (mn=sum*pro ).toFixed(2));	
	$("#d3").html( (sum*1).toFixed(2));		
</script>
</div>
<?php
return;
}
?>

<div class="card pb-0">
<h5 class="card-header text-center text-uppercase">Выберите способ пополнения игрового баланса:</h5>
<div class="row m-2 ">
	<div class="col-lg-6">
	<a href="/user/insert/payeer" class="card p-5 bg-light mb-1" style="background: url(/img/pay/payeer.png) no-repeat center center;background-size: 240px;"><br/><br/><br/></a>
	</div>
	<div class="col-lg-6">
	<a href="/user/insert/freekassa" class="card p-5 bg-light mb-1" style="background: url(/img/pay/free.png) no-repeat center center;background-size: 240px;"><br/><br/><br/></a>
	</div>
</div>

</div>