<?php if(!defined('FastCore')){echo ('Выявлена попытка взлома!');exit();}

class wallets {

	# Payeer фильтрация
	public function payeer_wallet($purse){
		if( substr($purse,0,1) != "P") return false;
		if( !preg_match("#^[0-9]{7,12}$#", substr($purse,1)) ) return false;	
	return $purse;
	}

	# Qiwi фильтрация
	public function qiwi_wallet($purse){
		if( !preg_match("#^[\+]{1}[7]{1}[9]{1}[\d]{9}$#",$purse) ) return false;
	return $purse;
	}
 	
	# Яндекс фильтрация
	public function yandex_wallet($purse){
		if( !preg_match("#^41001[0-9]{7,11}$#",$purse) ) return false;
         return $purse;
	}
}
?>