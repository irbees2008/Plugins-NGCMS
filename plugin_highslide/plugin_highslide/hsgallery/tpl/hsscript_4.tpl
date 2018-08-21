<style type="text/css" media="all">
@import url({admin_url}/hacks/highslide/highslide_full.css);
</style>
<!-- highslide start  -->
<script type="text/javascript" src="{admin_url}/hacks/highslide/highslide_full.js"></script>
<script type="text/javascript">    
hs.graphicsDir = '{admin_url}/hacks/highslide/graphics/';
hs.transitions = ['expand', 'crossfade'];
hs.restoreCursor = null;
hs.lang = {
	cssDirection: 'ltr',
	loadingText: 'Загружается...',
	loadingTitle: 'Нажмите для отмены',
	focusTitle: 'Нажмите чтобы поместить на передний план',
	fullExpandTitle: 'Развернуть до оригинального размера',
	creditsText: '',
	creditsTitle: '',
	previousText: 'Предыдущее',
	nextText: 'Следующее',
	moveText: 'Переместить',
	closeText: 'Закрыть',
	closeTitle: 'Закрыть (esc)',
	resizeTitle: 'Изменить размер',
	playText: 'Слайдшоу',
	playTitle: 'Начать слайдшоу (пробел)',
	pauseText: 'Пауза',
	pauseTitle: 'Приостановить слайдшоу (пробел)',
	previousTitle: 'Предыдущее (стрелка влево)',
	nextTitle: 'Следующее (стрелка вправо)',
	moveTitle: 'Переместить',
	fullExpandText: 'Оригинальный размер',
	number: 'Фото %1 из %2',
	restoreTitle: 'Нажмите чтобы закрыть изображение, нажмите и перетащите для изменения местоположения. Для просмотра изображений используйте стрелки.',
};

hs.addSlideshow({
	//slideshowGroup: 'group1',
	interval: 5000,
	repeat: true,
	useControls: true,
	overlayOptions: {
		position: 'bottom right',
		offsetY: 50
	},
	thumbstrip: {
		position: 'above',
		mode: 'horizontal',
		relativeTo: 'expander'
	}
});
var inPageOptions = {
	//slideshowGroup: 'group1',
	outlineType: '{hsc2}',
	allowSizeReduction: true,
	wrapperClassName: 'in-page controls-in-heading {hsc1}',
	useBox: true,
	width: 600,
	height: 400,
	targetX: 'gallery-area 10px',
	targetY: 'gallery-area',
	captionEval: 'this.thumb.alt',
	numberPosition: 'caption'
}
hs.addEventListener(window, 'load', function() {
	document.getElementById('thumb1').onclick();
});
hs.Expander.prototype.onImageClick = function() {
	if (/in-page/.test(this.wrapper.className))	return hs.next();
}
hs.Expander.prototype.onBeforeClose = function() {
	if (/in-page/.test(this.wrapper.className))	return false;
}
hs.Expander.prototype.onDrag = function() {
	if (/in-page/.test(this.wrapper.className))	return false;
}
hs.addEventListener(window, 'resize', function() {
	var i, exp;
	hs.getPageSize();
	for (i = 0; i < hs.expanders.length; i++) {
		exp = hs.expanders[i];
		if (exp) {
			var x = exp.x,
				y = exp.y;

			// get new thumb positions
			exp.tpos = hs.getPosition(exp.el);
			x.calcThumb();
			y.calcThumb();

			// calculate new popup position
		 	x.pos = x.tpos - x.cb + x.tb;
			x.scroll = hs.page.scrollLeft;
			x.clientSize = hs.page.width;
			y.pos = y.tpos - y.cb + y.tb;
			y.scroll = hs.page.scrollTop;
			y.clientSize = hs.page.height;
			exp.justify(x, true);
			exp.justify(y, true);
			exp.moveTo(x.pos, y.pos);
		}
	}
});
</script>
<style type="text/css">
	.highslide-image {
		border: 1px solid black;
	}
	.highslide-controls {
		width: 90px !important;
	}
	.highslide-controls .highslide-close {
		display: none;
	}
	.highslide-caption {
		padding: .5em 0;
	}
</style>
<!-- highslide end  -->
