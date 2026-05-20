/*
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

$(document).ready(function() {

	//подключаем лезилоад
//	let images = document.querySelectorAll("img");
//	lazyload(images);


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

*/
/*
function closeButfunk () {
	$('#mask').hide();
	$('#prodblock').hide();
}

$(document).ready(function() {
	$('#mask').click(function () {
		$(this).hide();
		$('#prodblock').hide();
	});
});
*/
/*
document.addEventListener('click', (e) => {
    const el = e.target.closest('.open-modal');
    if (!el) return;
    e.preventDefault();
*/

document.addEventListener('DOMContentLoaded', () => {
	const menuIcon = document.querySelector('.mob-menu-icon');
	const closeMenuIcon = document.querySelector('.close-menu');
	const mobileMenu = document.querySelector('.mob-menu');

	if (menuIcon && mobileMenu) {
		//open a mobile menu
		menuIcon.addEventListener('click', () => {
			mobileMenu.classList.add('active');
		});

		//close a mobile menu
		closeMenuIcon.addEventListener('click', () => {
			mobileMenu.classList.remove('active');
		});
	}
});


class WModal{

	constructor(props) {
		this.config = props;
		this.init();
	}

	init(){
		this.body = document.querySelector('body');
		this.backgroundClassName = 'modal-background';
		this.activeClassName = 'active';
		this.modalClassName = 'modal';
		this.modalWrapClassName = 'modal-wrap';
		this.modalContainerClassName = 'modal-container';
		this.сsrfToken = this.token();
	}

	token(){
		return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	}

	open(){
		this.makeBack();
		this.make();
	}

	makeBack(){
		const modalBack = document.createElement('div');
		modalBack.setAttribute('class', this.backgroundClassName);
		this.body.append(modalBack);

		setTimeout(() => {
    		modalBack.classList.add(this.activeClassName);
		}, 10);
	}

	make(){
		const modal = document.createElement('div');
		modal.setAttribute('class', this.modalClassName);

		modal.addEventListener('click', (e) => {
    		if (e.target === modalWrap) {
		    	this.remove();
    		}
		});

		const modalWrap = document.createElement('div');
		modalWrap.setAttribute('class', this.modalWrapClassName);

		const modalContainer = document.createElement('div');
		modalContainer.setAttribute('class', this.modalContainerClassName);
		modalContainer.innerHTML = `<h3 class="title">` + this.config.title + `</h3>
							<p class="pad3">` + this.config.text + `</p>
							<form class="modal-form" method="POST" action="` + this.config.url + `">
								<input type="hidden" name="_token" autocomplete="off" value="` + this.сsrfToken + `">
								<input type="hidden" name="_method" value="DELETE">
								<div class="pad3">
				            		<button type="submit" class="button mr-10">Да</button>
	            					<button type="button" class="button ml-10 cancel">Нет</button>
								</div>
        					</form>`;

		modalWrap.append(modalContainer);

		const cancelBtn = modalContainer.querySelector('.cancel');
		cancelBtn.addEventListener('click', () => {
			this.remove();
		});

		modal.append(modalWrap);
		this.body.append(modal);
	}

	remove(){
		document.querySelector(`.${this.backgroundClassName}`).remove();
		document.querySelector(`.${this.modalClassName}`).remove();
	}
}

document.addEventListener('click', (e) => {
    const el = e.target.closest('.open-modal');
    if (!el) return;
    e.preventDefault();
	console.log(el.dataset);
	const myModal = new WModal({
		url: el.dataset.url,
		title: el.dataset.title,
		text: el.dataset.text
	});

	myModal.open();
});