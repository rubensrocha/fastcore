<?php if (!defined('FastCore')) {
    exit('Opss!');
}
#Заголовки
$opt = array(
    'title' => 'Отзывы о проекте',
    'keywords' => 'отзывы сайта, рекомендация пользователей, оценка сайта, отзывы пользователей',
    'description' => 'Читайте отзывы наших пользователей, или оставьте свой отзыв и оценку для сайта.'
);
?>
    <center><p>Читайте отзывы или оставьте свой отзыв о нашем проекте!</p></center>
<?php
$rows = $db->query("SELECT * FROM `db_reviews` WHERE `id` > 0")->numRows();

# Пагинация
$cnt = 10;
$nav = '/reviews';
$page = $pg->params[1] ?? 1;
$start = ((int)$page * $cnt) - $cnt;
$str_pag = ceil($rows / $cnt);

if ($rows === 0) {
    echo '<div class="alert alert-danger">Отзывы еще никто не оставлял, можете оставить свой отзыв!</div>';
} else {
    $reviews = $db->query('SELECT * FROM `db_reviews` ORDER BY `id` DESC LIMIT ' . $start . ',' . $cnt . '')->fetchAll();
    foreach ($reviews as $ur) {
        ?>
        <div class="card p-2 mb-3">
            <h5 class="card-title mb-0">Отзыв пользователя: <b><?= $ur['login'] ?></b> <small
                    class="badge badge-warning float-right"><b><?= date("d.m.Y в H:i", $ur['date']) ?></b></small>
                <hr class="my-1">
            </h5>
            <p class="mb-0"><?= $ur['text'] ?></p>
        </div>
        <?php
    }

    # Выводим пагинацию
    if ($rows > $cnt) {
        echo '<ul class="pagination"><li class="page-item"><a class="page-link" href="' . $nav . '">«</a></li>';
        for ($i = 1; $i <= $str_pag; $i++) {
            echo '<li class="page-item"><a class="page-link" href="' . $nav . '/p/' . $i . '">' . $i . '</a></li>';
        }
        echo '<li class="page-item"><a class="page-link" href="' . $nav . '/p/' . $str_pag . '">»</a></li></ul>';
    }
}

$limit = $db->query("SELECT * FROM db_reviews WHERE uid = '$uid'")->numRows();

$comm = $db->query("SELECT * FROM db_users WHERE id = '$uid'")->fetchArray();
$invest = $comm['sum_in'] ?? '0';
$login = $comm['login'] ?? 'Guest';

# Добавляем отзыв
if (isset($_POST['asd'])) {

    $date = time();
    $text = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
    if ($limit >= 1) {
        $err[] = 'Вы уже оставляли отзыв! Спасибо за активность!';
    } elseif (!isset($_SESSION["login"])) {
        $err[] = 'Необходимо пройти авторизацию, прежде чем добавить отзыв!';
    } elseif ($invest <= 9.99) {
        $err[] = 'Отзыв могут оставлять только активные пользователи, пополнившие баланс на сумму от 10 руб!';
    } elseif (mb_strlen($text) < 30 or mb_strlen($text) > 1000) {
        $err[] = 'Длина отзыва не может составлять минимум 30 максимум 1000 символов';
    } else {
        $db->query('INSERT INTO db_reviews (login, uid, text,`date`) VALUES (?,?,?,?)', array($login, $uid, $text, $date)); // Сохраняем
        echo '<div class="alert alert-success">Ваш отзыв успешно добавлен!</div>';
    }
}
# ошибки
if (!empty($err)) {
    echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle mr-3"></i> ' . array_shift($err) . '</div>';
}

if (isset($_SESSION["login"]) && $limit === 0) {
    ?>
    <center>
        <form method="POST" action="" class="card card-body" style="max-width: 700px;">
            <h4 class="card-title">Оставьте свой отзыв! <br/><small>Для нас очень важно ваше мнение о проекте!</small>
            </h4>
            <hr class="my-1">
            <div class="form-group mb-0">Ваш отзыв:
                <textarea class="form-control" name="content" rows="1"></textarea></div>
            <div class="form-group">
                <button name="asd" type="submit" class="btn btn-success btn-lg mt-1">Написать</button>
            </div>
        </form>
    </center>
<?php
}
