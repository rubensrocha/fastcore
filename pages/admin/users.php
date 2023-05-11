<?php if (!defined('FastCore')) {
    exit('Opss!');
}

# Инфа про юзера
if (isset($pg->segment[2]) && $pg->segment[2] === 'info') {

    $id = filter_var($pg->params[1], FILTER_VALIDATE_INT);
    $db->query("SELECT * FROM db_users WHERE id = '$id' LIMIT 1");

    if ($db->numRows() != 1) {
        echo '<div class="alert alert-danger m-2">Игрока с таким ID не существует!</div>';
        return;
    }

    $db->query("SELECT * FROM db_users WHERE id = '$id' LIMIT 1");
    $user = $db->fetchArray();

    ?>

    <h3>Редактирование пользователя #<?= $user['id'] ?></h3>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

            <ul class="list-group mb-3">
                <li class="list-group-item bg-light">Информация о пользователе <b
                        class="float-right"><?= $user['login'] ?></b></li>
                <li class="list-group-item"><b>Данные пользователя:</b><br/>
                    ID пользователя: <span class="float-right"><?= $user['id'] ?></span><br/>
                    Email пользователя: <span class="float-right"><?= $user['email'] ?></span><br/>
                    Реферер: <span class="float-right"><?= $user['referer'] ?> с ID <?= $user['rid'] ?></span><br/>
                    Дата регистрации: <span class="float-right"><?= date("d/m/Y в H:i", $user['reg']) ?></span><br/>
                    Дата входа: <span class="float-right"><?= date("d/m/Y в H:i", $user['auth']) ?></span><br/>
                    IP адрес: <span class="float-right"><?= $func->int_ip($user['ip']) ?></span><br/>
                    Пришел с сайта: <span class="float-right"><?= $user['refsite'] ?></span><br/>
                    Заблокирован: <span
                        class="float-right"><?= ($user['ban'] > 0) ? '<span class="badge badge-danger">Да</span>' : '<span class="badge badge-success">Нет</span>' ?></span>
                </li>
                <li class="list-group-item"><b>Финансовые данные:</b><br/>
                    Пополнил: <span class="float-right"><?= $user['sum_in'] ?> руб.</span><br/>
                    Баланс для покупок: <span class="float-right"><?= $user['money_b'] ?> руб.</span><br/>
                    Баланс для выплат: <span class="float-right"><?= $user['money_p'] ?> руб.</span><br/>
                    Выплачено: <span class="float-right"><?= $user['sum_out'] ?> руб.</span><br/>
                    Принес рефереру: <span class="float-right"><?= $user['ref_to'] ?> руб.</span><br/>
                    Рефералов и заработок: <span class="float-right"><?= $user['refs'] ?> (<?= $user['income'] ?> руб.)</span>
                </li>
            </ul>

        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="card mb-3">
                <div class="card-header">Действия с пользователем:
                    <form action="" method="POST" class="float-right m-0 p-0"
                          style="position: absolute; right: 10px;top: 8px;">
                        <input type="hidden" name="ban" value="<?= ($user['ban'] > 0) ? 0 : 1 ?>"/>
                        <button class="btn btn-sm btn-outline-dark float-right"
                                name="baneed"><?= ($user['ban'] > 0) ? 'Разблокировать' : 'Заблокировать' ?></button>
                    </form>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($_POST["baneed"])) {
                        $db->query("UPDATE db_users SET ban = '" . (int)$_POST["ban"] . "' WHERE id = '$id'");
                        echo "<div class='alert alert-success'>Пользователь был " . ($_POST["ban"] > 0 ? "заблокирован!" : "разблокирован!") . "</div>";
                    }
                    ?>

                    <?php
                    if (isset($_POST["balance"])) {
                        $sum = filter_var($_POST['sum'], FILTER_VALIDATE_FLOAT);
                        $bal = $_POST['schet'];
                        $type = ($_POST["balance"] == 1) ? "-" : "+";
                        $string = ($type == "-") ? "У пользователя снято {$sum} руб." : "Пользователю добавлено {$sum} руб.";
                        $db->query("UPDATE db_users SET {$bal} = {$bal} {$type} {$sum} WHERE id = '$id'");
                        echo "<div class='alert alert-success text-center'>$string</div>";
                    }
                    ?>
                    <b>Операции с балансом:</b>
                    <form action="" method="POST" class="row">
                        <div class="col-lg-6">
                            <select name="balance" class="form-control">
                                <option value="2">Добавить на баланс</option>
                                <option value="1">Снять с баланса</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <select name="schet" class="form-control">
                                <option value="money_b">Для покупок</option>
                                <option value="money_p">Для вывода</option>
                            </select></div>
                        <div class="col-lg-12">
                            <input type="text" name="sum" value="100" class="form-control form-control-sm mt-2"/>

                            <button class="btn btn-outline-dark mt-2" type="submit">Выполнить</button>

                        </div>

                    </form>


                </div>
            </div>
        </div>
    </div>

    </div>
    <?php
    return;
}
?>

    <h3 class="">Пользователи</h3>

    <form action="" method="POST" style="width: 250px;">
        <div class="input-group">
            <input type="text" name="login" class="form-control" placeholder="Введите логин"/>
            <div class="input-group-append">
                <input type="submit" class="btn btn-success" value="Поиск"/>
            </div>
        </div>
    </form>

<?php
# Страница пользователей
$page = $pg->params[1] ?? 1;
$rows = 100;  // кол-во записей для вывода
$start = ($page * $rows) - $rows; // следущая запись после $rows

# Если логин найден
if (isset($_POST["login"])) {
    $login = $_POST["login"];
    $db->query("SELECT * FROM db_users WHERE login = '$login'");
    $rows = 1;
    $navs = 0;
} else

    $db->query('SELECT * FROM `db_users` ORDER BY `id` DESC LIMIT ' . $rows . '');
if ($db->numRows() > 0) {
    ?>

    <table class="table table-sm table-bordered table-hover text-center bg-white">
        <thead class="bg-light">
        <th>ID</th>
        <th>Логин</th>
        <th>Баланс</th>
        <th>IN/OUT</th>
        <th>Referer</th>
        <th>Откуда</th>
        <th>Регистрация</th>
        </thead>

        <?php
        if (isset($_POST["login"])) {
            $login = $_POST["login"];
            $u_data = $db->query("SELECT * FROM db_users WHERE login = '$login'")->fetchAll();
        } else {
            $n_rows = $db->query("SELECT * FROM `db_users` WHERE `id` > 0")->numRows();
            $total = $n_rows;
            $navs = ceil($total / $rows);
            $u_data = $db->query('SELECT * FROM `db_users` ORDER BY `id` DESC LIMIT ' . $start . ',' . $rows . '')->fetchAll();
        }

        foreach ($u_data as $u) {
            ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><a href="/<?= $adm ?>/users/info/<?= $u['id'] ?>"><?= $u['login'] ?></a></td>
                <td><?= $u['money_b'] ?> | <?= $u['money_p'] ?> </td>
                <td><span class="text-success"><?= $u['sum_in'] ?> </span> / <span
                        class="text-danger"><?= $u['sum_out'] ?> </span></td>
                <td><?= $u['referer'] ?></td>
                <td><?= $u['refsite'] ?></td>
                <td><?= date("d/m/Y в H:i", $u['reg']) ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
    # Выводим страницы
    echo '<ul class="pagination"><li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">Страница</a></li>';
    for ($i = 1; $i <= $navs; $i++) {
        echo '<li class="page-item"><a class="page-link" href="/' . $adm . '/users/p/' . $i . '">' . $i . '</a></li>';
    }
    echo '</ul>';
} else {
    echo '<div class="alert alert-danger">На данной странице нет записей</div>';
}