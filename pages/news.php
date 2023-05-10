<?php if(!defined('FastCore')){exit('Opss!');}
# Заголовки
$opt = array(
'title' => 'Новости',
'keywords' => 'новости сайта, уведомления для сайта, оповещение пользователям, события',
'description' => 'Самые свежие и актуальные новости на нашем сайте, о конкурсах и акциях, прочитай чтобы не пропустить.'
);

?>
<?php

$rows = $db->query("SELECT * FROM `db_news` WHERE `id` > 0")->numRows();

# Пагинация
$cnt = 10;
$nav ='/news';
$page = $pg->params[1] ?? 1;
$start = ($page * $cnt) - $cnt;
$str_pag = ceil($rows / $cnt);

if($rows == 0) {
	echo '<div class="alert alert-danger">На данный момент новости не были опубликованы.</div>';
} 
else {
$news = $db->query('SELECT * FROM `db_news` ORDER BY `id` DESC LIMIT '.$start.','.$cnt.'')->fetchAll();
foreach ($news as $news) { 
?>
<div class="card mb-3">
<h5 class="card-header"><?=$news['title']; ?></h5>
<p class="card-body mb-0"><?=$news['text']; ?></p>
<div class="card-footer"><small>Публикация: <b><?=date("d.m.Y в H:i",$news['add']); ?></b></small></div>
</div>
<?php
	}
	# Выводим пагинацию
	if ($rows > $cnt) {
	echo '<ul class="pagination"><li class="page-item"><a class="page-link" href="'.$nav.'">«</a></li>';
	for ($i = 1; $i <= $str_pag; $i++){
		echo '<li class="page-item"><a class="page-link" href="'.$nav.'/p/'.$i.'">'.$i.'</a></li>';
		}
	echo '<li class="page-item"><a class="page-link" href="'.$nav.'/p/'.$str_pag.'">»</a></li></ul>';
	}
}
?>
