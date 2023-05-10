<?php if(!defined('FastCore')){exit('Opss!');}
# Заголовки
$opt = array(
'title' => 'Главная страница',
'keywords' => 'скрипт, хайпы, игры, бонусы, серфинги, php7, apache, nginx',
'description' => 'Супербыстрый скрипт для создания сайтов php7, mysqli, utf-8, apache, nginx'
);

# Вставляем в куки ID пригласителя /i/123
if (isset($pg->params[1])) {
$rid = (intval($pg->params[1]) > 0) ? intval($pg->params[1]) : 1;
setcookie('i',$rid,time()+(60*60*24*14), '/'); 
header('Location: /'); return;
}

# Статистика
$stats = $db->query("SELECT * FROM db_stats WHERE id = '1'")->fetchArray();
?>

<div class="jumbotron mb-2">
<h1><b><?=$config->sitename;?></b></h1>
 <p>Супербыстрый скрипт для создания сайтов на PHP-7</p><hr>
<div class="row mb-4" style="font-weight: bold;font-size: 105%;">
<div class="col-lg-2 col-md-3"><small>Скрипт</small><br/>FastCore v0.8</div>
<div class="col-lg-2 col-md-3"><small>Релиз</small><br/>08.08.2020</div>
<div class="col-lg-6 col-md-6"><small>Требования</small><br/>php7.3, mysqli, utf-8, apache, FastCGI</div>
</div>
<div><a href="https://vk.com/fastcore" class="btn btn-lg btn-primary">Скачать</a></div>
</div>

<div class="row text-center text-uppercase mb-3 mt-3">
<div class="col-md-3"><div class="wrapper p-2"><i class="fa fa-users" style="font-size: 135%;"></i><br/><b><?=$stats['users'];?> чел.</b><br/>Пользователей</div></div>
<div class="col-md-3"><div class="wrapper p-2"><i class="fa fa-briefcase" style="font-size: 135%;"></i><br/><b><?=$stats['inserts'];?> руб.</b><br/>Пополнено </div></div>
<div class="col-md-3"><div class="wrapper p-2"><i class="fa fa-ruble-sign" style="font-size: 135%;"></i><br/><b><?=$stats['payments'];?> руб.</b><br/>Выплачено </div></div>
<div class="col-md-3"><div class="wrapper p-2"><i class="fa fa-university" style="font-size: 135%;"></i><br/><b><?=intval(((time() - $config->start_time) / 86400 ) +1); ?></b> <br/>Дней работы </div></div>
</div>

<div class="row">
<div class="col-lg-8">
<div class="wrapper p-3">
<h4>Немного о скрипте</h4>
<p>Данный скрипт предназначен для создания сайтов любой сложности, в скрипте имеется базовый функционал, необходимый для изначального пользования, всеми привычные разделы от предшественников, теперь все это работает намного быстрее и безопаснее. Мы потрудились сделать этот скрипт максимально простым и удобным и к тому же масштабируемым, эта версия скрипта нуждается в доработке и потерпит некоторые изменения, скрипт нельзя продавать в чистом виде так как является публичным. Если у Вас есть идеи и разбираетесь в кодинге воплощайте их в данный движок, создавай модули, дизайны и прочие наработки они будут полезными.
</p>
</div>
</div>
<div class="col-lg-4">
<div style="font-size: 110%;" class="wrapper p-3">
Разработка: Jumast & Kolyaka105. <br/>
Наше сообщество - <a href="https://vk.com/fastcore">VK.COM/FASTCORE</a><br/>

<b>Требования к серверу:</b><br/>
 - php7.3 и выше<br/>
 - Mysqli UTF-8 | inno_db<br/>
 - Apache 2.4, FastCGI, CGI
</div>
</div>
<div>