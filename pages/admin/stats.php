<?php if (!defined('FastCore')) {
    exit('Opss!');
}
$limits = 500;
if (isset($pg->segment[2]) && !empty($pg->segment[2] === 'inserts')) $type = 1; // пополнения
if (isset($pg->segment[2]) && !empty($pg->segment[2] === 'payouts')) $type = 2;  // выплаты
if (isset($pg->segment[2]) && !empty($pg->segment[2] === 'store')) $type = 3; // покупки
?>

<?php
if ($type === 1) {
    ?>
    <h3>Статистика пополнений</h3>
    <table class="table table-sm table-bordered table-hover text-center bg-white">
        <thead class="bg-light">
        <th>ID</th>
        <th>Логин</th>
        <th>Пополнил</th>
        <th>Начислено</th>
        <th>Дата создания</th>
        <th>Дата завершение</th>
        </thead>
        <?php
        $insert = $db->query('SELECT * FROM db_insert WHERE status = 1  ORDER BY id DESC LIMIT ' . $limits . '')->fetchAll();
        foreach ($insert as $in) {
            ?>
            <tr>
                <td><?= $in['id'] ?></td>
                <td><a href="/<?= $adm ?>/users/info/<?= $in['uid'] ?>"><?= $in['login'] ?></a></td>
                <td><?= $in['sum'] ?> Руб.</td>
                <td><?= $in['sum_x'] ?> Руб.</td>
                <td><?= date("d/m/Y в H:i", $in['add']) ?></td>
                <td><?= date("d/m/Y в H:i", $in['end']) ?></td>
            </tr>
        <?php } ?>
    </table>

    <?php
} else if ($type === 2) {
    ?>
    <h3>Статистика выплат</h3>
    <table class="table table-sm table-bordered table-hover text-center bg-white">
        <thead class="bg-light">
        <th>ID</th>
        <th>Логин</th>
        <th>Вывел</th>
        <th>Кошелек</th>
        <th>Система</th>
        <th>Дата</th>
        </thead>
        <?php
        $payout = $db->query('SELECT * FROM db_payout WHERE status = 3  ORDER BY id DESC LIMIT ' . $limits . '')->fetchAll();
        foreach ($payout as $py) {
            ?>
            <tr>
                <td><?= $py['id'] ?></td>
                <td><a href="/<?= $adm ?>/users/info/<?= $py['uid'] ?>"><?= $py['login'] ?></a></td>
                <td><?= $py['sum'] ?> Руб.</td>
                <td><?= $py['purse'] ?></td>
                <td><?= $py['sys'] ?></td>
                <td><?= date("d/m/Y в H:i", $py['add']) ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php
} else if ($type === 3) {

    ?>
    <h3>Статистика покупок персонажей</h3>
    <table class="table table-sm table-bordered table-hover text-center bg-white">
        <thead class="bg-light">
        <th>ID</th>
        <th>Логин</th>
        <th>Название</th>
        <th>Скорость</th>
        <th>Дата сбора</th>
        <th>Покупка</th>
        <th>Закрытие</th>
        </thead>
        <?php

        $items = $db->query('SELECT * FROM db_store ORDER BY id DESC LIMIT ' . $limits . '')->fetchAll();
        foreach ($items as $buy) {
            $login = $db->query('SELECT login FROM db_users ORDER BY id = ' . $buy['uid'] . ' DESC LIMIT 1')->fetchArray();
            ?>
            <tr>
                <td><?= $buy['id'] ?></td>
                <td><a href="/<?= $adm ?>/users/info/<?= $buy['uid'] ?>"><?= $login['login'] ?></a></td>
                <td><?= $buy['title'] ?></td>
                <td><?= $buy['speed'] ?> Руб.</td>
                <td><?= date("d/m/Y в H:i", $buy['last']) ?></td>
                <td><?= date("d/m/Y в H:i", $buy['add']) ?></td>
                <td><?= date("d/m/Y в H:i", $buy['end']) ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php
}