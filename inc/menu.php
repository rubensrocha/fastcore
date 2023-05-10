<div class="clearfix"></div>
<div class="clear"></div>
<div class="leftbar"><div class="menubar">
    <div class="menu2 divide text-uppercase">
	<i class="fa fa-bars"></i><span>Игровое меню</span>
    </div>
    <div class="menu__wrapper pt-2">
<h4 class="pt-1 text-center text-white"> <b><?=$login;?></b> </h4>
        <div class="topbar leftbar__topbar">
            <div class="topbar__bottom">
                <div class="topbar__balance">
                    <p>Баланс покупок <br/><b><?=$user['money_b'];?></b> <span>руб.</span></p>
		<a class="btn btn-sm btn-success" href="/user/insert"><i class="fa fa-arrow-up"></i><span> <b>Пополнить</b></span></a>
                </div>
                <div class="divide topbar__balance">
                    <p>Баланс для вывода<br/><b><?=$user['money_p'];?></b> <span>руб.</span></p>
		<a class="btn btn-sm btn-danger" href="/user/pay"><i class="fa fa-arrow-down"></i><span> <b>Вывести</b></span></a>
                </div>
            </div>
	</div>

	<ul class="leftbar__menu">
		<li><a href="/user/dashboard" style="border: 0 !important;"><i class="fa fa-bars"></i><span>Мой профиль </span> </a></li>
		<li><a href="/user/shop"><i class="fa fa-bars"></i><span>Покупка тарифов</span></a></li>
		<li><a href="/user/store"><i class="far fa-money-bill-alt"></i><span>Собрать доход</span></a></li>
		<li><a  href="/user/bonus"><i class="fa fa-gift"></i><span>Ежедневный бонус</span></a></li>
		<li><a href="/user/exchange"><i class="fa fa-exchange-alt"></i><span>Обменник</span></a></li>
		<li><a href="/user/refs"><i class="fa fa-users"></i><span>Мои рефералы</span></a></li>
		<li><a href="/user/settings"><i class="fa fa-cog"></i><span>Настройки</span></a></li>
		<li><a href="/user/logout"><i class="fa fa-sign-out-alt"></i><span>Выход</span></a></li>
	</ul>
</div></div></div>