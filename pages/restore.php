<?php if(!defined('FastCore')){exit('Opss!');}
# Заголовки
$opt = array(
'title' => 'Восстановление пароля',
'keywords' => 'Восстановление пароля в проекте',
'description' => 'Восстановление пароля, вспомнить пароль, сбросить пароль');

if(isset($_SESSION['uid'])){ Header('Location: /user/dashboard'); return; }
?>
<h4>Введите Email который хотите восстановить</h4>

<?php

# Форма регистрации
if (isset($_POST['restore']) ){

# Фильтрация
$email = $func->FMail($_POST['email']);
$time = time();
$tdel = $time + 60*15;

$db->query("DELETE FROM db_restore WHERE date_del < ?",$time);

# Определить IP адрес
$real_ip = $func->get_ip();
$ip = $func->ip_int($real_ip);

# Ошибка email
if(!empty(filter_var($email, FILTER_VALIDATE_EMAIL) !== false)) {
	
# Ищем пользователя
$uml = $db->query("SELECT * FROM db_users WHERE email = ?",$email)->numRows();
if($uml == 1){

# Пароль восстанавливался за 15 минут
$restore = $db->query("SELECT * FROM db_restore WHERE ip = ? OR email = ?",$ip,$email)->numRows();
if($restore == 0){

	$new_pass = rand(1111111,9999999);
	# Вносим запись в БД
	$db->query("INSERT INTO db_restore (email, ip, date_add, date_del) VALUES (?,?,?,?)",$email,$ip,$time,$tdel);
	$db->query('UPDATE db_users SET pass = ? WHERE email = ?',array($new_pass,$email));

	$mail = new send_mail;
	$mail->send(''.$email.'', 'Восстановление пароля', 'Ваш новый пароль - '.$new_pass.'');
	echo '<div class="alert alert-success">На ваш E-Mail адрес было отправлено сообщение.</div>';

	} else { $errors[] = 'Восстановление пароля с этого IP ('.$real_ip.') уже производилось за последние 15 минут!'; }
	} else { $errors[] = 'Пользователь с таким email не найден!'; }
	} else { $errors[] = 'Ошибка заполнения email!'; }

# Вывод ошибок
if (!empty($errors)) {
	echo '<div class="alert alert-danger"><i class="fa fa-warning"></i> '.array_shift($errors).'</div>';
}

}
?>
<form action="" method="POST">
<div class="form-group mb-1"><input class="form-control" name="email" type="email" placeholder="Email" value=""></div>

<button name="restore" type="submit" class="btn btn-success">ВОССТАНОВИТЬ</button>
</form>