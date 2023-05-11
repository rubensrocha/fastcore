<?php if (!defined('FastCore')) {
    exit('Opss!');
}
# Заголовки
$opt = array(
    'title' => 'Вход',
    'keywords' => 'вход в проекте',
    'description' => 'вход, войти в аккаунт, войти');

if (isset($_SESSION['uid'])) {
    Header('Location: /user/dashboard');
    return;
}
?>
<h4>Форма авторизации</h4>
<?php
# Форма входа
if (isset($_POST['auth'])) {

    $login = $func->FLogin($_POST['email']);
    $email = $func->FMail($_POST['email']);
    $pass = $func->FPass($_POST['pass']);

# Определить IP адрес
    $real_ip = $func->get_ip();
    $ip = $func->ip_int($real_ip);

# Если пустое поле
    if (empty($_POST['email'] and $_POST['pass'])) {
        $errors[] = 'Не все поля заполнены!';
    }

# Фильтрация данных
    if (empty(filter_var($email, FILTER_VALIDATE_EMAIL) !== false) && empty($login)) {
        $errors[] = 'Email или Логин заполнен неверно';
    }

    # Ищем email / login
    $users = $db->query('SELECT * FROM db_users WHERE email = ? OR login = ? LIMIT 1', array($email, $login))->fetchArray();

    # Проверка email / login
    if (!isset($users['email']) && $email) {
        $errors[] = 'Email не найден!';
    }
    if (!isset($users['login']) && $login) {
        $errors[] = 'Логин не найден!';
    }

    if (isset($users['pass'])) {
        # Проверка пароля
        if (strtolower($users['pass']) !== strtolower($pass)) {
            $errors[] = 'Пароль не совпадает!';
        }
        # Если забанен
        if ($users['ban'] === 1) {
            $errors[] = 'Ваш аккаунт был заблокирован!';
        }
    }

# Успешный вход
    if (empty($errors)) {
        $time = time();
        $userID = $users['id'];
        $db->query('UPDATE db_users SET ip = ?, auth = ? WHERE id = ?', array($ip, $time, $userID));
        $_SESSION['uid'] = $users['id'];
        $_SESSION['login'] = $users['login'];
        header('Location: /user/dashboard');
        return;
    }

    # Вывод ошибок
    echo '<div class="alert alert-danger"><i class="fa fa-warning"></i> ' . array_shift($errors) . '</div>';
}
?>

<form action="" method="POST">
    <div class="form-group mb-1"><input class="form-control" name="email" placeholder="Email или Логин"></div>
    <div class="form-group mb-1"><input type="password" class="form-control" name="pass" placeholder="Пароль"></div>

    <button name="auth" type="submit" class="btn btn-success">ВХОД</button>
    <a class="btn btn-white text-primary" href="/restore">Забыли пароль?</a>
</form>