function openFadeIFrame (e) {
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
	$('#mask').css({'width':maskWidth,'height':maskHeight});
	$('#mask').fadeTo("fast",0.5);	
	windSize = getWindowSize(this);	
	var winW = windSize[0];
	var winH = windSize[1];
	if (winH < 700) {
		//Set the popup window to center
		var topWnd =  Math.round(winH/2-980/2);
		if (topWnd < 10) {
			topWnd = 0;
		}
	} else {
		//Set the popup window to center
		var topWnd =  Math.round(winH/2-680/2);
		if (topWnd < 10) {
			topWnd = 10;
		}
	}
	if (parseInt (e.width) > 0) {
		$(".bgFame1Cnt2").html ('');
		$('#prodblock').css ('width', e.winWidth +'px');
		$('#prodblock').css('top',  Math.round(winH/2-e.winHeight/2 + getScrollY())+'px');
		$('#prodblock').css('left', Math.round(winW/2-e.winWidth/2)+'px');
		$('#prodblock').css ('display', 'block');
		$(".bgFame1Cnt2").html('<div class="rel1 size4"><a href="javascript:return false;" class="bgBut12 left1" onclick="closeButfunk(); return false;"></a></div><iframe src ="'+ e.url + '" id="framein" style="width:' + e.width + 'px; height:' + e.height + 'px;" frameborder="0"></iframe>');
	} else {
		$(".bgFame1Cnt2").html ('');
		$('#prodblock').css ('width', e.winWidth +'px');
		$('#prodblock').css('top',  Math.round(winH/2-e.winHeight/2)+'px');
		$('#prodblock').css('left', Math.round(winW/2-e.winWidth/2)+'px');
		$('#prodblock').css ('display', 'block');
		$(".bgFame1Cnt2").load(e.url);
	}
}

function openFrame1 (e) {
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
	$('#mask').css({'width':maskWidth,'height':maskHeight});
	$('#mask').fadeTo("fast",0.5);	
	windSize = getWindowSize(this);	
	var winW = windSize[0];
	var winH = windSize[1];
	
	$(".bgFame1Cnt2").html ('');
	$('#prodblock').css ('width', e.winWidth +'px');
	$('#prodblock').css('top',  Math.round(winH/2-e.winHeight/2 + getScrollY())+'px');
	$('#prodblock').css('left', Math.round(winW/2-e.winWidth/2)+'px');
	$('#prodblock').css ('display', 'block');
	var Frame = $('#' + e.idFrame).html();
	$(".bgFame1Cnt2").html('<div class="rel1 size4"><a href="javascript:return false;" class="bgBut12" onclick="closeButfunk(); return false;"></a></div><div id="prodblockOUT">' + Frame + '</div>');

}

function getWindowSize(wnd) {

	var windowWidth, windowHeight;

	if (wnd.innerHeight) { // all except Explorer
		if (wnd.document.documentElement.clientWidth) {
			windowWidth = wnd.document.documentElement.clientWidth;
		} else {
			wnd.windowWidth = wnd.innerWidth;
		}
		windowHeight = wnd.innerHeight;
	} else if (wnd.document.documentElement
			&& wnd.document.documentElement.clientHeight) { // Explorer 6 Strict
		// Mode
		windowWidth = wnd.document.documentElement.clientWidth;
		windowHeight = wnd.document.documentElement.clientHeight;
	} else if (wnd.document.body) { // other Explorers
		windowWidth = wnd.document.body.clientWidth;
		windowHeight = wnd.document.body.clientHeight;
	}

	return [windowWidth, windowHeight];
}

function closeButfunk () {
	$('#mask').hide();
	$('#prodblock').hide();
}

$(document).ready(function() {
	$('#mask').click(function () {
		$(this).hide();
		$('#prodblock').hide();
	});
	
	//открываем мобильное меню
	$('.mob-menu-icon').click(function () {
		$('.mob-menu').show();
	});

	//закрываем мобильное меню	
	$('.close-menu').click(function () {
		$('.mob-menu').hide();
	});


	//подключаем лезилоад
//	let images = document.querySelectorAll("img");
//	lazyload(images);

/*
	$(function() {
		$("img").lazyload({
			effect : "fadeIn"
		});
	});
*/


});

function getScrollY() {
	scrollY = 0;
	if (typeof window.pageYOffset == "number") {
		scrollY = window.pageYOffset;
	} else if (document.documentElement && document.documentElement.scrollTop) {
		scrollY = document.documentElement.scrollTop;
	}  else if (document.body && document.body.scrollTop) {
		scrollY = document.body.scrollTop;
	} else if (window.scrollY) {
		scrollY = window.scrollY;
	}
	return scrollY;
}


document.addEventListener('click', (e) => {
    const el = e.target.closest('.open-frame');
    if (!el) return;

    e.preventDefault();

    openFadeIFrame({
        url: el.dataset.url,
        winWidth: el.dataset.width,
		winHeight: el.dataset.height,
    });
});