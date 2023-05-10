$(function() {

	//открываем меню
	function menuOpen() {
    if($(window).width() <= 791) {
			$('.menu').click(function() {
				$('.nav, .header').addClass('active');
			});
		}
  	}menuOpen();

	//закрываем меню
	$('.close_menu, .header.active').click(function() {
		$('.nav, .header').removeClass('active');
		return false;
	});

	//закрываем меню по клику вне блока
	$(document).mouseup(function (e){
    var menu = $('.nav');
    if (!menu.is(e.target) && menu.has(e.target).length === 0) {
	    //закрываем меню
	    $('.nav, .header').removeClass('active');
	    // return false;
    }
  });

  //выполняем функции при смене разрешения
	$(window).resize(function() {
    menuOpen();
    //CmenuOpen();
	});



});
function CmenuOpen() {
  if (document.querySelector('html .menu2') != null) {
		document.querySelector('html .menu2').addEventListener('click', function(){
		  document.querySelector('html').classList.toggle('min-menu');
		})
  }

}CmenuOpen();