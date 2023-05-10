<?php if(!defined('FastCore')){echo ('Выявлена попытка взлома!');exit();}


$domain = str_replace("www.","",$_SERVER['HTTP_HOST']);
$send_email = 'support@'.$domain.'';

define("MAILSITENAME", ''.$domains.'');
define("MAILCHARSET", "utf8");
define("MAILADMIN",''.$send_email.'');

class send_mail {
    var $send_error = TRUE; // Вывод ошибки

   # Создание заголовков
    function compile_headers() {
        $this->subject = "=?" . MAILCHARSET . "?b?" . base64_encode($this->subject) . "?=";
        $from = "=?" . MAILCHARSET . "?b?" . base64_encode(MAILSITENAME) . "?=";
        $this->mail_headers = "MIME-Version: 1.0" . "\n";
        $this->mail_headers .= "Content-type: text/plain; charset=\"" . MAILCHARSET . "\"" . "\n";
        $this->mail_headers .= "Subject: " . $this->subject . "\n";
        $this->mail_headers .= "To: " . $this->to . "\n";
        $this->mail_headers .= "From: \"" . $from . "\" <" . MAILADMIN . ">" . "\n";
        $this->mail_headers .= "Return-Path: <" . MAILADMIN . ">" . "\n";
        $this->mail_headers .= "X-Priority: 3" . "\n";
        $this->mail_headers .= "X-Mailer: PHP" . "\n";
    }
   # Функция оправки сообщения
    function send($to, $subject, $message) {
        $this->to = $to;
        $this->subject = $subject;
        $this->compile_headers();
        if ($this->to && MAILADMIN && $this->subject) {
            if(!@mail($this->to, $this->subject, $message, $this->mail_headers)) {
                $this->send_error = true;
            }
        }
    }
}
?>