
--
-- Структура таблицы `db_conf`
--

CREATE TABLE IF NOT EXISTS `db_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coint` int(11) NOT NULL,
  `bounty` int(11) NOT NULL,
  `p_sell` int(11) NOT NULL,
  `p_swap` int(11) NOT NULL,
  `min_s` float(10,2) NOT NULL,
  `acc_pay` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `db_conf`
--

INSERT INTO `db_conf` (`id`, `coint`, `bounty`, `p_sell`, `p_swap`, `min_s`, `acc_pay`) VALUES
(1, 1, 10, 90, 5, 0.10, 10);

--
-- Структура таблицы `db_percent`
--

CREATE TABLE IF NOT EXISTS `db_percent` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`type` int(11) NOT NULL,
`sum_a` int(11) NOT NULL,
`sum_b` int(11) NOT NULL,
`sum_x` decimal(10,2) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `db_percent`
--

INSERT INTO `db_percent` (`id`, `type`, `sum_a`, `sum_b`, `sum_x`) VALUES
(1, 1, 1, 499, 0.1),
(2, 1, 500, 999, 0.2),
(3, 1, 1000, 4999, 0.3),
(4, 1, 5000, 9999, 0.4),
(5, 1, 10000, 99999, 0.5);

--
-- Структура таблицы `db_store`
--

CREATE TABLE `db_store` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
`uid` int(11) NOT NULL,
`title` varchar(30) NOT NULL,
`tarif` int(11) NOT NULL,
`level` int(11) NOT NULL,
`speed` decimal(10,4) NOT NULL,
`add` int(15) NOT NULL,
`end` int(15) NOT NULL,
`last` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Структура таблицы `db_tarif`
--

CREATE TABLE IF NOT EXISTS `db_tarif` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(30) NOT NULL,
`img` int(11) NOT NULL,
`speed` decimal(10,4) NOT NULL,
`price` int(11) NOT NULL,
`period` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `db_tarif`
--

INSERT INTO `db_tarif` (`id`, `title`, `img`, `speed`, `price`, `period`) VALUES
(1, 'тест-1', 1, 0.0034, 10, 90),
(2, 'тест-2', 2, 0.0180, 50, 90),
(3, 'тест-3', 3, 0.0930, 250, 90),
(4, 'тест-4', 4, 0.3800, 1000, 90),
(5, 'тест-5', 5, 1.2000, 3000, 90),
(6, 'тест-6', 6, 3.2200, 7500, 90);

--
-- Структура таблицы `db_news`
--

CREATE TABLE `db_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `cat` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `add` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Структура таблицы `db_reviews`
--

CREATE TABLE `db_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(25) NOT NULL,
  `uid` int(11) NOT NULL,
  `text` text NOT NULL,
  `img` int(11) NOT NULL DEFAULT '0',
  `like` int(11) NOT NULL DEFAULT '0',
  `hide` int(11) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Структура таблицы `db_bonus`
--

CREATE TABLE `db_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `login` varchar(50) NOT NULL,
  `sum` float(10,2) NOT NULL,
  `add` int(11) NOT NULL DEFAULT '0',
  `del` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Структура таблицы `db_insert`
--

CREATE TABLE `db_insert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `sum` float(10,2) NOT NULL,
  `sum_x` float(10,2) NOT NULL DEFAULT '0',
  `sys` varchar(20) NOT NULL,
  `status` int(11) NOT NULL,
  `add` int(11) NOT NULL DEFAULT '0',
  `end` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Структура таблицы `db_payout`
--

CREATE TABLE `db_payout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `login` varchar(30) NOT NULL,
  `purse` varchar(20) NOT NULL,
  `sum` double NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `sys` int(11) NOT NULL,
  `add` int(11) NOT NULL DEFAULT '0',
  `del` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Структура таблицы `db_stats`
--

CREATE TABLE `db_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users` int(11) NOT NULL DEFAULT '0',
  `inserts` float(10,2) NOT NULL DEFAULT '0.00',
  `payments` float(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп таблицы `db_stats`
--

INSERT INTO `db_stats` VALUES (1,0,0.00,0.00);

--
-- Структура таблицы `db_restore`
--

CREATE TABLE `db_restore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `ip` int(11) NOT NULL,
  `date_add` int(11) NOT NULL,
  `date_del` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `db_purse`
--

CREATE TABLE `db_purse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `payeer` varchar(50) NOT NULL DEFAULT '0',
  `qiwi` varchar(50) NOT NULL DEFAULT '0',
  `yandex` varchar(50) NOT NULL DEFAULT '0',
  `pin` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Структура таблицы `db_users`
--

CREATE TABLE `db_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL,
  `email` varchar(60) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `reg` int(11) NOT NULL,
  `auth` int(11) NOT NULL DEFAULT '0',
  `ban` int(1) NOT NULL DEFAULT '0',
  `ip` int(20) unsigned NOT NULL DEFAULT '0',
  `money_b` float(10,2) NOT NULL DEFAULT '0.00',
  `money_p` float(10,2) NOT NULL DEFAULT '0.00',
  `sum_in` float(10,2) NOT NULL DEFAULT '0.00',
  `sum_out` float(10,2) NOT NULL DEFAULT '0.00',
  `refsite` varchar(60) NOT NULL DEFAULT '',
  `referer` varchar(30) NOT NULL DEFAULT '',
  `rid` int(11) NOT NULL DEFAULT '0',
  `refs` int(11) NOT NULL DEFAULT '0',
  `income` float(10,2) NOT NULL,
  `ref_to` float(10,2) NOT NULL,
  `point` double(10,2) NOT NULL DEFAULT '0.00',
  `rating` double(10,2) NOT NULL DEFAULT '0.00',
  `role` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;