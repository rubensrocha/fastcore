<?php if (!defined('FastCore')) {
    exit('Opss!');
}
# Заголовки
$opt = array(
    'title' => 'Регистрация',
    'keywords' => 'регистрация в проекте',
    'description' => 'регистрация, создать аккаунт, войти');

if (isset($_SESSION['uid'])) {
    Header('Location: /user/dashboard');
    return;
}
?>
<h4>Форма регистрации</h4>
<?php

# Форма регистрации
if (isset($_POST['reg'])) {

    # Фильтрация
    $login = $func->FLogin(htmlspecialchars($_POST["login"]));
    $email = $func->FMail($_POST["email"]);
    $pass = $func->FPass($_POST["pass"]);

# Хешируем пароль
//$pass = password_hash($pass_d, PASSWORD_DEFAULT);

    $time = time();

    # Источник перехода
    $site = $func->getDomain();

    # Кто пригласил
    $rid = (isset($_COOKIE["i"])) ? (int)$_COOKIE["i"] : 1;
    $referer = $rid == 0 ? 0 : $db->query('SELECT login FROM db_users WHERE id = ? LIMIT 1', array($rid))->fetchArray();
    if ($referer === null) {
        $rid = 1;
        $referer = "Admin";
    }

    # Определить IP адрес
    $real_ip = $func->get_ip();
    $ip = $func->ip_int($real_ip);

    # Проверка на валидность
    if (empty($login)) {
        $errors[] = 'Ошибка заполнения логин!';
    }
    if (empty(filter_var($email, FILTER_VALIDATE_EMAIL) !== false)) {
        $errors[] = 'Ошибка заполнения email!';
    }
    if (empty($pass)) {
        $errors[] = 'Ошибка заполнения пароля!';
    }

    # Проверка на уникальность
    $users = $db->query('SELECT * FROM db_users WHERE ip = ? OR login = ? OR email = ?', array($ip, $login, $email))->fetchArray();
    if (isset($users['ip']) && $users['ip'] === $ip) {
        $errors[] = 'Регистрация с этого IP (' . $real_ip . ') уже производилась!';
    }
    if (isset($users['login']) && $users['login'] === $login) {
        $errors[] = 'Такой Логин уже существует!';
    }
    if (isset($users['email']) && $users['email'] === $email) {
        $errors[] = 'Такой Email уже зарегистрирован!';
    }

    # Успешная регистрация
    if (empty($errors)) {

        # Создаем пользователя
        $db->query('INSERT INTO db_users (login, email, pass, reg, ip, rid, referer, refsite) VALUES (?,?,?,?,?,?,?,?)', array($login, $email, $pass, $time, $ip, $rid, $referer, $site));
        $lid = $db->LastInsert();

        # Создаем таблицу кошельков
        $db->query('INSERT INTO db_purse (id,uid,time) VALUES (?,?,?)', array($lid, $lid, $time));

        # Прибавляем рефоводу +1
        $db->query('UPDATE `db_users` SET `refs` = `refs` + 1 WHERE `id` = ' . $rid . '');

        # Пишем в статистику
        $db->query("UPDATE `db_stats` SET `users` = `users` + 1 WHERE `id` = '1'");

        echo '<div class="alert alert-success"><b>Регистрация прошла успешно!</b><br/>Сейчас Вы попадете на страницу входа.</div>';
        header('Refresh: 3; URL=/login');
        return;
    }

    # Вывод ошибок
    echo '<div class="alert alert-danger"><i class="fa fa-warning"></i> ' . array_shift($errors) . '</div>';
}
?>
<form action="" method="POST">
    <div class="form-group mb-1"><input class="form-control" name="login" type="text" placeholder="Логин" value="">
    </div>
    <div class="form-group mb-1"><input class="form-control" name="email" type="email" placeholder="Email" value="">
    </div>
    <div class="form-group mb-1"><input class="form-control" name="pass" type="password" placeholder="Пароль" value="">
    </div>

    <button name="reg" type="submit" class="btn btn-success">ЗАРЕГИСТРИРОВАТЬСЯ</button>
</form>