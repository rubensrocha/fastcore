<?php if (!defined('FastCore')) {
    exit('Opss!');
}

# Заголовок
$opt['title'] = 'Ежедневный бонус';

# Бонус выдача
$dep = round($user['sum_in']);

# Уровни
if ($dep <= 99) {
    $day = 5;
} elseif ($dep >= 100 and $dep < 999) {
    $day = random_int(5, 20);
} elseif ($dep >= 1000 and $dep < 2499) {
    $day = random_int(25, 50);
} elseif ($dep >= 2500 and $dep < 4999) {
    $day = random_int(100, 250);
} elseif ($dep >= 5000) {
    $day = 250;
}

# Настройки бонусов
$bonus_min = $day;
$bonus_max = $day;

?>
<div class="alert bg-light text-center">
    Бонус выдается каждые 24 часа. <br/>
    Сумма бонуса генерируется случайно от <font class="text-danger"><b>0.05</b></font> до <font class="text-danger"><b>2.50</b></font>
    руб.<br/>
    Бонус зависит от суммы пополнения чем больше пополнено тем выше бонус.<br/>
    <b class="text-danger text-uppercase" data-toggle="collapse" data-target="#lvl" style="cursor: pointer;"><i
            class="fa fa-question-circle" aria-hidden="true"></i> Таблица уровней бонуса:</b>

    <div id="lvl" class="collapse alert-light text-dark">
        Сумма пополнения меньше 100 руб. = <b>бонус 0.05 руб.</b><br/>
        Сумма пополнения 100 - 999 руб. = <b>бонус от 0.05 до 0.20 руб.</b><br/>
        Сумма пополнения 1000 - 2499 руб. = <b>бонус от 0.25 до 0.50 руб.</b><br/>
        Сумма пополнения 2500 - 4999 руб. = <b>бонус от 1.00 до 2.50 руб.</b><br/>
        Сумма пополнения свыше 5000 руб. = <b>бонус 2.50 руб.</b><br/>
    </div>
</div>

<?php
$ddel = time() + 60 * 60 * 24;
$dadd = time();
$hide = false;

$true = $db->query("SELECT * FROM `db_bonus` WHERE `uid` = '$uid' AND `del` > '$dadd'")->numRows();
if ($true == 0) {

# Выдача бонуса
    if (isset($_POST["bonus"])) {
        $random = rand($bonus_min, rand($bonus_min, $bonus_max));
        $sum = round($random / 100, 2);
        # Зачилсяем бонус
        $db->query("UPDATE db_users SET `money_b` = `money_b` + '$sum' WHERE `id` = '$uid'");
        # Вносим запись в список бонусов
        $db->query('INSERT INTO db_bonus (`login`, `uid`, `sum`, `add`, `del`) VALUES (?,?,?,?,?)', array($login, $uid, $sum, $dadd, $ddel));
        # Случайная очистка устаревших записей
        $db->query("DELETE FROM db_bonus WHERE `del` < '$dadd'");
        echo '<div class="alert alert-success">На Ваш счет для покупок зачислен бонус.</div>';
        $hide = true;
    }

# Скрыть кнопку
    if (!$hide) {
        ?>
        <center class="mb-1">
            <b class="text-uppercase">Перейдите по баннеру и нажмите получить бонус</b><br/>
            <div id="hidden_link" onclick="document.all.hidden_link1.style.display='block';"
                 style="width: 468px;display:block">
                нажми на меня
            </div>
            <div id="hidden_link1" onclick="document.all.hidden_link2.style.display='block';" style="display:none">
                <form action="" method="post">
                    <input type="submit" name="bonus" value="Получить бонус" class="btn btn-lg btn-success mt-1">
                </form>
            </div>
        </center>
        <?php
    }
} else {
    $udata = $db->query("SELECT * FROM db_bonus WHERE uid = '$uid'")->fetchArray();

    # Таймер
    $dt = $udata['del'] - time();
    $dd = (int)($dt / 86400);
    $hh = (int)(($dt - $dd * 86400) / 3600);
    $mm = (int)(($dt - $dd * 86400 - $hh * 3600) / 60);
    $ss = (int)($dt - $dd * 86400 - $hh * 3600 - $mm * 60);
    ?>
    <center class="alert alert-success border-success text-uppercase pb-2">До следующего бонуса осталось: <br/>
        <h5><i class="far fa-clock"></i>
            <b><?= sprintf("%02d <small>час</small> %02d <small>мин</small> %02d <small>сек</small>", $hh, $mm, $ss) ?></b>
        </h5>
    </center>
    <?php
}
?>

<h5 class="text-center">Последние 20 бонусов</h5>
<table class="table table-bordered text-center">
    <thead>
    <th>ID</th>
    <th>Пользователь</th>
    <th>Сумма</th>
    <th>Дата</th>
    </thead>
    <?php
    $bnum = $db->query("SELECT * FROM `db_bonus` WHERE `id` > 0")->numRows();
    if ($bnum >= 1) {
        $bon = $db->query("SELECT * FROM `db_bonus` ORDER BY `id` DESC LIMIT 20")->fetchAll();
        foreach ($bon as $b) {
            ?>
            <tr>
                <td><?= $b['id'] ?></td>
                <td><?= $b['login'] ?></td>
                <td><?= $b['sum'] ?></td>
                <td><?= date("d.m.Y в H:i:s", $b['add']) ?></td>
            </tr>
            <?php
        }
    } else {
        echo '<tr><td colspan="4">Список бонусов пуст</td></tr>';
    }
    ?>
</table>