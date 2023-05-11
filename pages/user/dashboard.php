<?php if (!defined('FastCore')) {
    exit('Opss!');
}

# Заголовок
$opt['title'] = 'Профиль';

# Пользователь вышел
if ($pg->segment[1] === 'logout') {
    session_destroy();
    header('Location: /');
    return;
}

# Реф-ссылка
$url = ('https://' . $_SERVER['HTTP_HOST'] . '/i/' . $uid);
?>

<div class="row">

    <div class="col-lg-6">
        <div class="card mt-2">
            <h5 class="card-header">Мои данные:</h5>
            <div class="card-body">
                <h6>Ваш ID: <?= $user['id'] ?></h6>
                <h6>Ваш логин: <?= $user['login'] ?></h6>
                <h6>Ваша почта: <?= $user['email'] ?></h6>
                <h6>Дата регистрации: <?= date("d.m.Y в H:i", $user['reg']) ?></h6>
                <h6>Пригласил: <?= $user['referer'] ?></h6>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mt-2">
            <h5 class="card-header">Моя статистика:</h5>
            <div class="card-body">
                <h6>Баланс покупок: <?= sprintf("%.2f", $user['money_b']) ?> <i class="fa fa-rub"></i></h6>
                <h6>Баланс выплат: <?= sprintf("%.2f", $user['money_p']) ?> <i class="fa fa-rub"></i></h6>
                <h6>Пополнили: <?= sprintf("%.2f", $user['sum_in']) ?> <i class="fa fa-rub"></i></h6>
                <h6>Выплатили: <?= sprintf("%.2f", $user['sum_out']) ?> <i class="fa fa-rub"></i></h6>
                <h6>Реф-доход: <?= sprintf("%.2f", $user['income']) ?> <i class="fa fa-rub"></i></h6>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card card-body mt-3 bg-light">
            <h5 class="card-title">Партнерская программа (10% от пополнений!):</h5>
            <div class="input-group mb-2">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-link"></i></span></div>
                <input type="text" onclick="this.select()" class="form-control" value="<?= $url ?>">
            </div>
        </div>
    </div>

</div>