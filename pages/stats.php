<?php if (!defined('FastCore')) {
    exit('Opss!');
}
# Заголовки
$opt = array(
    'title' => 'Статистика',
    'keywords' => 'статистика, пользователи, топ, лидеры, старт, проект',
    'description' => 'Статистика нашего проекта, Вы можете посмотреть лидеров и активность игроков.'
);

# Статистика
$stats = $db->query("SELECT * FROM db_stats WHERE id = '1'")->fetchArray();
?>

<div class="row text-center text-uppercase mb-3">
    <div class="col-md-3">
        <div class="wrapper p-2"><i class="fa fa-users" style="font-size: 135%;"></i><br/><b><?= $stats['users'] ?>
                чел.</b><br/>Пользователей
        </div>
    </div>
    <div class="col-md-3">
        <div class="wrapper p-2"><i class="fa fa-briefcase"
                                    style="font-size: 135%;"></i><br/><b><?= $stats['inserts'] ?> руб.</b><br/>Пополнено
        </div>
    </div>
    <div class="col-md-3">
        <div class="wrapper p-2"><i class="fa fa-ruble-sign"
                                    style="font-size: 135%;"></i><br/><b><?= $stats['payments'] ?> руб.</b><br/>Выплачено
        </div>
    </div>
    <div class="col-md-3">
        <div class="wrapper p-2"><i class="fa fa-university"
                                    style="font-size: 135%;"></i><br/><b><?= (int)(((time() - $config->start_time) / 86400) + 1) ?></b>
            <br/>Дней работы
        </div>
    </div>
</div>

<div class="row p-2">
    <div class="col-md-6 p-1">
        <div class="card p-1">
            <h5>Последние 20 пополнений</h5>
            <table class="table table-sm table-striped text-center mb-0">
                <thead>
                <th class="text-left">Логин</th>
                <th>Сумма</th>
                <th class="text-right">Время</th>
                </thead>
                <?php
                $inserts = $db->query('SELECT * FROM db_insert WHERE status = 1 ORDER BY id DESC LIMIT 20')->fetchAll();
                foreach ($inserts as $inserts) {
                    ?>
                    <tr>
                        <td class="text-left"><i class="fa fa-user"></i> <?= $inserts['login'] ?></td>
                        <td><?= sprintf("%.2f", $inserts['sum']) ?> руб.</td>
                        <td class="text-right"><?= date("d/m/Y в H:i", $inserts['add']) ?> <i class="far fa-clock"></i>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>


    <div class="col-md-6 p-1">
        <div class="card p-1">
            <h5>Последние 20 выплат</h5>
            <table class="table table-sm table-striped text-center mb-0">
                <thead>
                <th class="text-left">Логин</th>
                <th>Сумма</th>
                <th class="text-right">Время</th>
                </thead>
                <?php
                $payout = $db->query('SELECT * FROM db_payout WHERE status = 3  ORDER BY id DESC LIMIT 20')->fetchAll();
                foreach ($payout as $payout) {
                    ?>
                    <tr>
                        <td class="text-left"><i class="fa fa-user"></i> <?= $payout['login'] ?></td>
                        <td><?= sprintf("%.2f", $payout['sum']) ?> руб.</td>
                        <td class="text-right"><?= date("d/m/Y в H:i", $payout['add']) ?> <i class="far fa-clock"></i>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>


    <div class="col-md-4 p-1">
        <div class="card p-1">
            <h5>Топ 10 инвесторов</h5>
            <table class="table table-sm table-striped text-center mb-0">
                <thead>
                <th>Логин</th>
                <th>Сумма</th>
                </thead>
                <?php
                $ins = $db->query('SELECT * FROM db_users WHERE sum_in  ORDER BY sum_in DESC LIMIT 10')->fetchAll();
                foreach ($ins as $ins) {
                    ?>
                    <tr>
                        <td><i class="fa fa-user"></i> <?= $ins['login'] ?></td>
                        <td><?= sprintf("%.2f", $ins['sum_in']) ?> руб.</td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>

    <div class="col-md-4 p-1">
        <div class="card p-1">
            <h5>Топ 10 рефоводов</h5>
            <table class="table table-sm table-striped text-center mb-0">
                <thead>
                <th>Логин</th>
                <th>Сумма</th>
                </thead>
                <?php
                $refs = $db->query('SELECT * FROM db_users WHERE refs  ORDER BY refs DESC LIMIT 10')->fetchAll();
                foreach ($refs as $refs) {
                    ?>
                    <tr>
                        <td><i class="fa fa-user"></i> <?= $refs['login'] ?></td>
                        <td><?= $refs['refs'] ?> чел.</td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>

    <div class="col-md-4 p-1">
        <div class="card p-1">
            <h5>Топ 10 по заработку</h5>
            <table class="table table-sm table-striped text-center mb-0">
                <thead>
                <th>Логин</th>
                <th>Сумма</th>
                </thead>
                <?php
                $out = $db->query('SELECT * FROM db_users WHERE sum_out  ORDER BY sum_out DESC LIMIT 10')->fetchAll();
                foreach ($out as $out) {
                    ?>
                    <tr>
                        <td><i class="fa fa-user"></i> <?= $out['login'] ?></td>
                        <td><?= sprintf("%.2f", $out['sum_out']) ?> руб.</td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>

</div>
