<?php if (!defined('FastCore')) {
    exit('Opss!');
}

# Статистика
$stats = $db->query("SELECT * FROM db_stats WHERE id = '1'")->fetchArray();

$times = time() - 60 * 60 * 24;
$times2 = time() - 60 * 30;
$times3 = time() - 60 * 60 * 24 * 7;

$users_rows = $db->query("SELECT * FROM `db_users` WHERE `reg` > '$times'")->numRows();
$users_rows2 = $db->query("SELECT * FROM `db_users` WHERE `auth` > '$times2'")->numRows();
$users_rows3 = $db->query("SELECT * FROM `db_users` WHERE `auth` > '$times3'")->numRows();

# Количество 24 часа
$users24 = $users_rows;

# Онлайн за 30 минут
$online = $users_rows2;

# Онлайн за 7 дней
$online7 = $users_rows3;
?>
<div>
    <h3>Панель администратора</h3>
    <div class="row text-center text-uppercase alert-light text-dark m-1">
        <div class="col-lg-3 col-md-6 p-2">
            <div class="bg-light p-1"><i class="fa fa-users"></i><br/> Пользователей: <?= $stats['users'] ?> чел.</div>
        </div>
        <div class="col-lg-3 col-md-6 p-2">
            <div class="bg-light p-1"><i class="fa fa-users"></i><br/> За 24 часа: <?= $users24 ?> чел.</div>
        </div>
        <div class="col-lg-3 col-md-6 p-2">
            <div class="bg-light p-1"><i class="fa fa-users"></i><br/> Онлайн: <?= $online ?> чел.</div>
        </div>
        <div class="col-lg-3 col-md-6 p-2">
            <div class="bg-light p-1"><i class="fa fa-users"></i><br/> Активных: <?= $online7 ?> чел.</div>
        </div>
        <div class="col-lg-3 col-md-6 p-2">
            <div class="bg-light p-1"><i class="fa fa-briefcase"></i><br/> Пополнено: <?= $stats['inserts'] ?> руб.
            </div>
        </div>
        <div class="col-lg-3 col-md-6 p-2">
            <div class="bg-light p-1"><i class="fa fa-rub"></i><br/> Выплачено: <?= $stats['payments'] ?> руб.</div>
        </div>
        <div class="col-lg-3 col-md-6 p-2">
            <div class="bg-light p-1"><i class="fa fa-university"></i><br/>
                Резерв: <?= $stats['inserts'] - $stats['payments'] ?> руб.
            </div>
        </div>
    </div>
</div>

