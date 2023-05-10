<?php if(!defined('FastCore')){exit('Opss!');}

# Заголовок
$opt['title'] = 'Рефералы';

# Экономия места
$refs = $user['refs'];
$url = ('https://'.$_SERVER['HTTP_HOST']);
$lp = ($url.'/img/promo/');
?>

<div class="alert bg-light text-center">
Приглашайте в проект своих друзей и знакомых! Вы будете <br>получать <b>10%</b> от каждого пополнения баланса вашим рефералом сразу на счет для вывода! <br> Ниже представлена ссылка для привлечения и количество приглашенных Вами людей.<br>
</div>

<center class="container col-lg-8">
<label><b>Реферальная ссылка: </b></label>
<div class="input-group mb-2">
	<input type="text" onclick="this.select()" class="form-control" value="<?=$url.'/i/'.$uid; ?>">
	<div class="input-group-append">
	<button type="button" class="btn btn-success" data-toggle="collapse" data-target="#demo">Рекламные материалы</button>
</div>
</div>
</center>

<div id="demo" class="collapse"><hr>

<div class="row">
	<div class="col-md-8">
<div class="card">
<div class="p-2"><h5 class="card-title">Размер баннера: <b>468х60</b></h5>
<img src="<?=$lp;?>468.gif" alt="img">
<input type="text" onclick="this.select()" class="form-control mt-2" value="<?=$lp;?>468.gif">
</div></div>

<div class="card mt-2 mb-2">
<div class="p-2"><h5 class="card-title">Размер баннера: <b>728х90</b></h5>
<img src="<?=$lp;?>728.gif" style="width: 100%;" alt="img">
<input type="text" onclick="this.select()" class="form-control mt-2" value="<?=$lp;?>728.gif">
</div>
</div>
	</div>

	<div class="col-md-4">
<div class="card">
	<div class="p-2"><h5 class="card-title">Размер баннера: <b>200х300</b></h5>
<img src="<?=$lp;?>200.gif" alt="img">
<input type="text" onclick="this.select()" class="form-control mt-2" value="<?=$lp;?>200.gif">
</div>
	</div>

	</div>
</div><hr>
</div>


<h5 class="text-center p-2">Количество ваших рефералов: <b><?=$refs; ?></b> чел.</h5>
<table class="table table-striped table-bordered nowrap text-center table-sm">
<thead>
<tr>
	<th> Логин</th>
	<th> Доход от партнера</th>
	<th> Откуда пришел</th>
	<th> Дата регистрации</th>
</tr>
</thead>
<?PHP
$ref = $db->query("SELECT login, ref_to, refsite, reg FROM db_users WHERE id = id AND rid = '$uid' ORDER BY ref_to DESC")->fetchAll();
	foreach($ref as $r){
?>
<tr>
	<td><?=$r['login']; ?></td>
	<td><?=sprintf("%.2f",$r['ref_to']); ?></td>
	<td><?=$r['refsite']; ?></td>
	<td><?=date("d.m.Y в H:i",$r['reg']); ?></td>
</tr>
<?PHP
	}
?>
</table>