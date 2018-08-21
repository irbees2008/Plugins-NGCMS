<style type="text/css" media="all">
@import url({admin_url}/hacks/highslide/highslide_full.css);
</style>
<!-- highslide start  -->
<script type="text/javascript" src="{admin_url}/hacks/highslide/highslide_full.js"></script>
<script type="text/javascript">    
    hs.graphicsDir = '{admin_url}/hacks/highslide/graphics/';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.fadeInOut = true;
hs.outlineType = '{hsc2}';
hs.wrapperClassName = '{hsc1}';
hs.captionEval = 'this.a.title';
hs.numberPosition = 'caption';
hs.useBox = true;
hs.width =500;
hs.height = 500;
hs.dimmingOpacity = 0.8;
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
	restoreTitle: 'Нажмите чтобы закрыть изображение, нажмите и перетащите для изменения местоположения. Для просмотра изображений используйте стрелки.'
};
hs.addSlideshow({
		//slideshowGroup: 'group1',
		interval: 5000,
		repeat: false,
		useControls: true,
		fixedControls: 'fit',
		overlayOptions: {
			position: 'top right',
			offsetX: 200,
			offsetY: -65
		},
		thumbstrip: {
			position: 'rightpanel',
			mode: 'float',
			relativeTo: 'expander',
			width: '210px'
		}
	});
</script>
<!-- highslide end  -->
