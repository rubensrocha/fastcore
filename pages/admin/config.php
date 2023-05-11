<?php if (!defined('FastCore')) {
    exit('Opss!');
}

$db->query("SELECT * FROM db_conf WHERE id = '1' LIMIT 1");
$cnf = $db->fetchArray();
?>
<h3>Настройки сайта</h3>
<div class="row">
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header">Бонус при пополнении</div>
            <div class="p-2">
                <?php

                if (isset($_POST['percent'])) {
                    $bnx = $db->query("SELECT * FROM `db_percent`")->fetchAll();

                    foreach ($bnx as $s) {
                        $id = $_POST['id' . $s['id'] . ''];
                        $sum_a = $_POST['sum_a' . $s['id'] . ''];
                        $sum_b = $_POST['sum_b' . $s['id'] . ''];
                        $sum_x = $_POST['sum_x' . $s['id'] . ''];

                        $db->query("UPDATE db_percent SET sum_a = '$sum_a', sum_b = '$sum_b', sum_x = '$sum_x' WHERE id = '$id'");
                    }
                    echo '<div class="alert alert-success text-center">Проценты изменены</div>';
                }
                ?>
                <form action="" method="post" class="m-0">
                    <div class="row text-center">
                        <div class="col-lg-4"><b>Минимум <br/>(РУБ)</b></div>
                        <div class="col-lg-4"><b>Максимум<br/> (РУБ)</b></div>
                        <div class="col-lg-4"><b>Процент<br/> (0.10 = 10%)</b></div>
                    </div>

                    <?php
                    $bonx = $db->query("SELECT * FROM `db_percent` ORDER BY `id` DESC LIMIT 10")->fetchAll();
                    foreach ($bonx as $p) {
                        ?>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?= $p['id'] ?></span>
                            </div>
                            <input type="hidden" name="id<?= $p['id'] ?>" value="<?= $p['id'] ?>">
                            <input type="text" name="sum_a<?= $p['id'] ?>" value="<?= $p['sum_a'] ?>"
                                   class="form-control">
                            <input type="text" name="sum_b<?= $p['id'] ?>" value="<?= $p['sum_b'] ?>"
                                   class="form-control">
                            <input type="text" name="sum_x<?= $p['id'] ?>" value="<?= $p['sum_x'] ?>"
                                   class="form-control">
                        </div>
                        <?php
                    }
                    ?>
                    <button type="submit" name="percent" class="btn btn-success">Сохранить</button>
                </form>
            </div>
        </div>
    </div>


    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header">Конфиг</div>
            <div class="p-2">
                <?php
                if (isset($_POST['cnf'])) {
                    $coint = (int)$_POST['coint'];
                    $bounty = (int)$_POST['bounty'];
                    $acc_pay = (int)$_POST['acc_pay'];
                    $min_s = (float)$_POST['min_s'];
                    $p_sell = (int)$_POST['p_sell'];
                    $p_swap = (int)$_POST['p_swap'];

                    $db->query("UPDATE db_conf SET coint = '$coint', bounty = '$bounty', acc_pay = '$acc_pay', min_s = '$min_s', p_sell = '$p_sell', p_swap = '$p_swap' WHERE id = '1'");
                    echo '<div class="alert alert-success text-center">Настройки сохранены!</div>';
                    header('Refresh:2');
                }
                ?>
                <form action="" method="post" class="m-0">
                    <b>Курс валюты, пример 1 руб = 100 монет):</b>
                    <input class="form-control mb-2" type="text" name="coint" size="45" value="<?= $cnf['coint'] ?>"/>
                    <b>Бонус при регистрации <i class="fa fa-rub"></i>:</b>
                    <input class="form-control mb-2" name="bounty" size="45" value="<?= $cnf['bounty'] ?>">
                    <b>Заглушка для открытия выплат <i class="fa fa-rub"></i>:</b>
                    <input class="form-control mb-2" name="acc_pay" size="45" value="<?= $cnf['acc_pay'] ?>">
                    <b>Минимум для сбора <i class="fa fa-rub"></i>:</b>
                    <input class="form-control mb-2" name="min_s" size="45" value="<?= $cnf['min_s'] ?>">
                    <b>Процент на вывод при сборе:</b>
                    <input class="form-control mb-2" name="p_sell" size="45" value="<?= $cnf['p_sell'] ?>">
                    <b>Процент в обменнике(+ N-% к сумме обмена):</b>
                    <input class="form-control mb-2" name="p_swap" size="45" value="<?= $cnf['p_swap'] ?>">
                    <button type="submit" name="cnf" class="btn btn-success">Сохранить</button>
                </form>
            </div>
        </div>

    </div>
</div>