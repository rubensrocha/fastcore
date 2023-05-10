<div class="col-md-2 p-0 navbar-dark bg-dark fcmenu">
<style>
.fcmenu .nav-item {border-bottom: 1px solid rgba(85,87,89,0.2);padding-left: 10px;}
.fcmenu .nav-item i{margin-right: 5px;width: 20px;text-align: center;}
.fcm {color: #6c757d;}
</style>

<div class="bg-warning pl-1 pb-1 text-dark">
<b><i class="fa fa-bars ml-1" style="margin-right: 5px;width: 20px;text-align: right;"></i> Админ-панель</b>
</div>
	<ul class="navbar-nav">
		<li class="nav-item"><a class="nav-link" href="/<?=$adm;?>/"><i class="fa fa-bar-chart-o"></i> Статистика</a></li>
		<li class="nav-item"><a class="nav-link" href="/<?=$adm;?>/news"><i class="fa fa-rss"></i> Новости</a></li>
		<li class="nav-item"><a class="nav-link" href="/<?=$adm;?>/st/inserts"><i class="fa fa-plus"></i> Пополнения</a></li>
		<li class="nav-item"><a class="nav-link" href="/<?=$adm;?>/st/payouts"><i class="fa fa-minus"></i> Выплаты</a></li>
		<li class="nav-item"><a class="nav-link" href="/<?=$adm;?>/st/store"><i class="fa fa-drupal"></i> Список покупок</a></li>
		<li class="nav-item"><a class="nav-link" href="/<?=$adm;?>/users"><i class="fa fa-users"></i> Пользователи</a></li>
		<li class="nav-item"><a class="nav-link" href="/<?=$adm;?>/pers"><i class="fa fa-drupal"></i> Персонажи</a></li>
		<li class="nav-item"><a class="nav-link" href="/<?=$adm;?>/config"><i class="fa fa-gear"></i> Настройки</a></li>
	</ul>
</div>

<div class="col-md-10 p-0">
<div class="bg-dark pl-2 pr-2 pb-1 navbar-dark fcm">
<a class="text-light" title="Основная страница" href="/<?=$adm; ?>"><i class="fa fa-home"></i></a> |
<a class="text-light" title="Переоткрыть страницу" href=""><i class="fa fa-refresh"></i></a> |
<a class="text-light" href="/"><i class="fa fa-link"></i> Перейти на сайт</a> 
<a class="text-light pull-right" href="/<?=$adm;?>/exit"><i class="fa fa-sign-out"></i> Выйти</a>
</div>

<div class="col-md-12 p-0">
	<div class="col-md-12">