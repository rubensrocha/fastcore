<?php if (!defined('FastCore')) {
    exit('Oops!');
}

class func
{

    # ============================
    # Калькулятор сбора прибыли
    # ============================
    public function SumCalc($per_h, $sum_tree, $last_sbor)
    {
        if ($last_sbor > 0) {
            if ($sum_tree > 0 and $per_h > 0) {
                sprintf("%.6f", $sum_tree);
                $last_sbor = ($last_sbor < time()) ? (time() - $last_sbor) : 0;
                $per_sec = $per_h;
                return round(($per_sec / 3600) * $last_sbor, 4);
            }
            return 0;
        }
        return 0;
    }

    # ============================
    # Преобразует IP в целочисленное
    # ============================
    public function ip_int($uip)
    {
        $uip = ip2long($uip);
        ($uip < 0) ? $uip += 4294967296 : true;
        return $uip;
    }

    # ============================
    # Преобразует целочисленное в IP
    # ============================
    public function int_ip($int)
    {
        return long2ip($int);
    }

    # ============================
    # Получаем валидный IP
    # ============================
    public function get_ip()
    {
        $ipp = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] as $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $ipp = $xip;
                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
            $ipp = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ipp = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
            $ipp = $_SERVER['HTTP_X_REAL_IP'];
        }
        return $ipp;
    }

    # ============================
    # Функция источник перехода
    # ============================
    public function getDomain()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer = parse_url(trim($_SERVER['HTTP_REFERER']));
            if (empty($_COOKIE['site'])) {
                setcookie('site', $referer['host'], time() + (60 * 60 * 24 * 14), '/');
            }
            return trim($referer['host']);
        }
        return null;
    }

    # ============================
    # Фильтрация Логина
    # ============================
    public function FLogin($login, $mask = "^[а-яА-ЯЁёa-zA-Z0-9_]", $len = "{4,20}")
    {
        if(is_array($login)){
            return false;
        }
        if(preg_match("/{$mask}{$len}$/u", $login)){
            return $login;
        }
        return false;
    }

    # ============================
    # Фильтрация Пароля
    # ============================
    public function FPass($pass, $mask = "^[!@#$%*а-яА-ЯЁёa-zA-Z0-9_]", $len = "{4,20}")
    {
        if(is_array($pass)){
            return false;
        }
        if(preg_match("/{$mask}{$len}$/u", $pass)){
            return $pass;
        }
        return false;
    }

    # ============================
    # Фильтрация Почты
    # ============================
    public function FMail($email)
    {
        if (is_array($email) && empty($email) && strlen($email) > 255 && strpos($email, '@') > 64) {
            return false;
        }
        // validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,8}$/ix", $email)) ? false : strtolower($email);
    }
}