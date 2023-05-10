<?php if(!defined('FastCore')){exit('Opss!');}

# Заголовок
$opt['title'] = 'Обменник';

# Значения
$min_sum = '10';

$db->query("SELECT * FROM db_conf WHERE id = '1' LIMIT 1");
$cnf = $db->fetchArray();

?>


<?PHP
# Обменник 

if(isset($_POST["change"])){
$sum = (float)$_POST["change"];
	if($sum >= $min_sum){
		if($user['money_p'] >= $sum){		
		$add_sum = sprintf("%.2f",($cnf['p_swap'] > 0) ? ( ($cnf['p_swap'] / 100) * $sum) + $sum : $sum);
		$db->query('UPDATE db_users SET money_b = money_b + ?, money_p = money_p - ? WHERE id = ?',array($add_sum,$sum, $uid));
		echo '<center class="alert alert-success">Обмен произведен (+'.$add_sum.' руб.)</center>';	
		}else echo '<center class="alert alert-warning">Недостаточно средств</center>';
	}else echo '<center class="alert alert-danger">Минимум '.$min_sum.' руб.</center>';
}
?>

<h5 class="text-center">Обменяйте баланс с вывода на баланс для покупок и получите <b>+<?=$cnf['p_swap']; ?>%</b> при обмене.</h5>
<div class="row">
<div class="col-lg-4"></div>
<div class="col-lg-4">
<div class="text-center">
<form action="" method="post">
<div class="input-group mb-2">
	<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-ruble-sign"></i></span></div>
	<input type="text" class="form-control" id="change" name="change" onkeyup="GetSumPer();" value="10" />
<div class="input-group-append"><span class="input-group-text" title="Получите на счет для покупок"><small>+</small> <span id="res_sum" name="res">10.5</span></span></div>
</div>
	<input type="hidden" name="per" id="percent" value="<?=$cnf['p_swap']; ?>" disabled="disabled"/>
	<input type="submit" value="Обменять" class="btn btn-warning"/>
</form>
<script language="javascript">GetSumPer();</script>
<script>

function GetSumPer(){
	var sum = parseFloat(document.getElementById("change").value);
	var percent = parseFloat(document.getElementById("percent").value);
	var add_sum = 0;

	if(sum > 0){
		if(percent > 0){
			add_sum = (percent / 100) * sum;
		}
		document.getElementById("res_sum").innerHTML = Math.round(sum+add_sum);
	}
}
</script>
</div></div>
</div>
