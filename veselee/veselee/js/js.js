$(document).ready(function() {

	$('.select').fancySelect();
	
    $.datepicker.regional['ru'] = {
            closeText: 'Закрыть',
            prevText: '&#x3c;Пред',
            nextText: 'След&#x3e;',
            currentText: 'Сегодня',
            monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
            'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
            'Июл','Авг','Сен','Окт','Ноя','Дек'],
            dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
            dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
            dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
            weekHeader: 'Не',
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''};
    $.datepicker.setDefaults($.datepicker.regional['ru']);
	
    $("#announce_datepicker").datepicker({
			inline: true,
			minDate: new Date
	});
		
	$("#announce_datepicker_filter").datepicker({
			inline: true,
			minDate: new Date
	});
	
	$("#registration_datepicker").datepicker({
			inline: true,
			dateFormat: 'yy-mm-dd'
	});
	
		
    $('#announce_timepicker').timepicker({
			inline: true,
			timeFormat: 'H:i'
	});
	
	$('#announce_timepicker_edit').timepicker({
			inline: true,
			timeFormat: 'H:i'
	});
	
	$("#announce_datepicker_edit").datepicker({
			inline: true,
			minDate: new Date
	});

    $(".demo").customScrollbar({onCustomScroll:function (event, scrollData) {
        var id = "#" + $(this).attr("id") + "-events-info";
        $(id + " .scroll-percent-value").html(scrollData.scrollPercent);
        $(id + " .scroll-direction-value").html(scrollData.direction);
        $(id + " .scroll-scroll-axis").html(scrollData.scrollAxis);
    }});
	

	jQuery.ajax({
		type: "GET",
		url: "http://ipgeobase.ru:7020/geo?ip="+myip+"",
		dataType: "xml",
		success: function(xml) {
		jQuery(xml).find('ip').each(
		function()
		{
			var city = jQuery(this).find('city').text(),
			region = jQuery(this).find('region').text();
			if(city!=region){
				ipg = city+", "+region;
			}else{
				ipg = city;
			}
			$('.reg_title').html(ipg);
		});
		}
	});
	
    
/* MODALS */
$('a[rel="modal"]').click(function() {
    var modalID = $(this).attr('href');
    var modBox = $(modalID).find('.modal-box');
    modBox.css('margin-left', modBox.width() / -2);
    
    $('body').append('<div class="shadow-bg"></div>');
    $('.shadow-bg').fadeIn();
    
    var modBoxClick = true;
    $(modalID).fadeIn();
    $(modalID).find('.modal-box').click(function() {
        modBoxClick = false
    });
    $(modalID).click(function() {
        if(modBoxClick) {
            $(modalID).fadeOut();
            $('.shadow-bg').fadeOut(function() {
                $(this).remove();
            });
        }
        modBoxClick = true;
    });
    $(modalID).find('.modal-clouse').click(function() {
        $(modalID).fadeOut();
        $('.shadow-bg').fadeOut(function() {
            $(this).remove();
        });
    });
    $(modalID).find('.loginza').click(function() {
        $(modalID).fadeOut();
        $('.shadow-bg').fadeOut(function() {
            $(this).remove();
        });
    });
    return false;
});

			
jcarousel = $('.jcarousel');

jcarousel
	.on('jcarousel:reload jcarousel:create', function () {
		var width = jcarousel.innerWidth();

		if (width >= 600) {
			width = width / 3;
		} else if (width >= 50) {
			width = width / 2;
		}

		jcarousel.jcarousel('items').css('width', width + 'px');
	})
	.jcarousel({
		wrap: null
	});

$('.jcarousel-control-prev1, .jcarousel-control-prev2, .jcarousel-control-prev3')
	.on('jcarouselcontrol:active', function() {
		$(this).removeClass('inactive');
	})
	.on('jcarouselcontrol:inactive', function() {
		$(this).addClass('inactive');
	})
	.jcarouselControl({
		target: '-=1'
	});

$('.jcarousel-control-next1, .jcarousel-control-next2, .jcarousel-control-next3')
	.on('jcarouselcontrol:active', function() {
		$(this).removeClass('inactive');
	})
	.on('jcarouselcontrol:inactive', function() {
		$(this).addClass('inactive');
	})
	.jcarouselControl({
		target: '+=1'
	});
	
});