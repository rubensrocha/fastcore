<?php if (!defined('FastCore')) {
    echo('Выявлена попытка взлома!');
    exit();
}

/**
 * Маршрутизатор (правила маршрутизации страниц).
 */

$GLOBALS['routes'] = array(
    '_404' => '../inc/404.php', // 404
    '/' => 'home.php', // Главная
    '/i/([0-9]+)?' => '/home.php', // Реф-ссылка
    '/stats' => 'stats.php', // Статистика
    '/login' => 'login.php', // Вход
    '/reg' => 'reg.php', // Регистрация
    '/restore' => 'restore.php', // Восстановить пароль
    '/news' => 'news.php', // Новости
    '/news/p/([0-9]+)?' => 'news.php',
    '/reviews' => 'reviews.php', // Отзывы
    '/reviews/add' => 'reviews.php', // Отзывы
    '/reviews/p/([0-9]+)?' => 'reviews.php',
    '/about' => 'about.php', // О проекте
    '/terms' => 'terms.php', // Правила
    '/help' => 'help.php', // Поддержка

    # Аккаунт
    '/user/dashboard' => 'dashboard.php', // Профиль
    '/user/bonus' => 'bonus.php', // Бонусы
    '/user/shop' => 'shop.php', // Персонажи покупка
    '/user/store' => 'store.php', // Сбор прибыли
    '/user/insert' => 'insert.php', // Пополнить
    '/user/insert/payeer' => 'insert.php', // Пополнить payeer
    '/user/insert/freekassa' => 'insert.php', // Пополнить freekassa
    '/user/pay' => 'pay.php', // Спобом заказа выплаты
    '/user/pay/([^/]+)' => 'pay.php', // Выплата payeer
    //'/user/pay/yandex' => 'pay.php', // Выплата yandex
    //'/user/pay/qiwi' => 'pay.php', // Выплата qiwi
    '/user/exchange' => 'exchange.php', // Обменник
    '/user/refs' => 'referals.php', // Рефералы
    '/user/settings' => 'settings.php', // Настройки
    '/user/logout' => 'dashboard.php', // Выход
    '/user/success' => 'success.php', // Успех
    '/user/fail' => 'fail.php', // Неудача

    # Админка
    '/' . $adm . '' => 'login.php', // Вход
    '/' . $adm . '' => 'main.php', // Главная
    '/' . $adm . '/' => 'main.php', // Главная слеш
    '/' . $adm . '/config' => 'config.php', // Настройки
    '/' . $adm . '/users' => 'users.php', // Пользователи
    '/' . $adm . '/users/info/([0-9]+)?' => 'users.php', // Пользователь
    '/' . $adm . '/users/p/([0-9]+)?' => 'users.php', // Пользователи страницы
    '/' . $adm . '/st/([^/]+)' => 'stats.php', // Статистика
    '/' . $adm . '/news' => 'news.php', // Новости
    '/' . $adm . '/news/add' => 'news.php', // Новости
    '/' . $adm . '/news/edit/([0-9]+)?' => 'news.php', // Новости
    '/' . $adm . '/pers' => 'pers.php', // Персонажи
    '/' . $adm . '/pers/add' => 'pers.php', // Персонажи
    '/' . $adm . '/pers/edit/([0-9]+)?' => 'pers.php', // Персонажи
);