<style type="text/css" media="all">
@import url({admin_url}/hacks/highslide/highslide_s.css);
</style>
<!-- highslide start  -->
<script type="text/javascript" src="{admin_url}/hacks/highslide/highslide_s.js"></script>
<script type="text/javascript">    
    hs.graphicsDir = '{admin_url}/hacks/highslide/graphics/';
hs.wrapperClassName = '{hsc1}';
hs.captionEval = 'this.thumb.alt';
hs.numberPosition = 'caption';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.outlineType = '{hsc2}';
hs.fadeInOut = true;
hs.dimmingOpacity = 0.85;
hs.useBox = true;
hs.width = 640;
hs.height = 480;
hs.addSlideshow({
		//slideshowGroup: 'group1',
		interval: 5000,
		repeat: true,
		useControls: true,
		fixedControls: 'fit',
		overlayOptions: {
			opacity: 1,
			position: 'bottom center',
			hideOnMouseOut: false
	}
});
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
</script>
<!-- highslide end  -->
