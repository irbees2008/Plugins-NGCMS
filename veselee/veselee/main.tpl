[TWIG]
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset={{ lang['encoding'] }}" />
	<meta http-equiv="content-language" content="{{ lang['langcode'] }}" />
	<meta name="generator" content="{{ what }} {{ version }}" />
	<meta name="document-state" content="dynamic" />
	{{ htmlvars }}
	<link rel="stylesheet" type="text/css" href="{{tpl_url}}/css/style.css">
	<link rel="stylesheet" type="text/css" href="{{tpl_url}}/css/smoothness/jquery-ui-1.9.2.custom.min.css">
	<!--<script type="text/javascript" src="{{tpl_url}}/js/jquery.min.js"></script> -->
	<script type="text/javascript" src="{{tpl_url}}/js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="{{tpl_url}}/js/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="{{tpl_url}}/js/html5.js"></script>
	<script type="text/javascript" src="{{tpl_url}}/js/jquery.tinyscrollbar.min.js"></script>
	<!-- <script type="text/javascript" src="{{tpl_url}}/js/js.js"></script> -->
	<script type="text/javascript" src="{{tpl_url}}/js/fancySelect.js"></script>
	<script src="{{ tpl_url }}/js/jcarousel.js" type="text/javascript"></script>
	<script src="{{ tpl_url }}/js/control.js" type="text/javascript"></script>
	<script type="text/javascript" src="{{tpl_url}}/js/js.js"></script>
	<script src="{{tpl_url}}/js/jPages.js"></script>
	{% if pluginIsActive('rss_export') %}<link href="{{ home }}/rss.xml" rel="alternate" type="application/rss+xml" title="RSS" />{% endif %}
	<script type="text/javascript" src="{{ scriptLibrary }}/functions.js"></script>
	<script type="text/javascript" src="{{ scriptLibrary }}/ajax.js"></script>
	<script type="text/javascript" src="http://l2.io/ip.js?var=myip"></script>
	<title>{{ titles }}</title>
</head>
<div id="loading-layer"><img src="{{ tpl_url }}/img/loading.gif" alt="" /></div>
<body>
	<div class="wrap">
		<header class="header">
	    	<div class="logo">
	    		<a href="{{home}}">
	    			<b>ВместеВеселее.рф</b>
	    			хороший	сервис хорошим людям
	    		</a>
	    	</div>
	    	<div class="choose_region">
	    		<span class="reg_title"></span>
                        <a href="" class="prew">изменить город</a>
	    		
	    	</div>
			{% if (global.flags.isLogged) %}
				{{ personal_menu }}
			{% else %}
			<div class="login" id="auth">
				<a href="#auth-modal" rel="modal" class="name">Войти</a>
			</div>
			{% endif %}

	    	<div class="clear"></div>
		</header>
		
		<div id="content">
	{% if  not isHandler('news:main') %}
		{{mainblock}}
	{% endif %}
	{% if  not isHandler('auth_loginza') and not isHandler('faq') and not isHandler('uprofile') and not isHandler('news:news') and not isHandler('events') %}
		{{ callPlugin('events.filter') }}
		{{ callPlugin('events.archive') }}
	{% endif %}

		</div>
		<aside class="right_col">
		{{ callPlugin('events.send') }}
		
		{% if (global.flags.isLogged) %}
		{{ callPlugin('events.show', {'mode' : 'last', 'toU': '1', 'pagination': '1' , 'expired': '', 'template': 'block_my_last'}) }}
		{% endif %}
		
		{% if (global.flags.isLogged) %}
		{{ callPlugin('events.show', {'mode' : 'last', 'toU': '1', 'pagination': '1' , 'expired': '1', 'template': 'block_my_archive'}) }}
		{% endif %}

		{{ callPlugin('faq.show', {'maxnum' : 3, 'order' : 'ASC'}) }}
		
		
		</aside>
		<div class="clear"></div>
                <div class="about_company">
				<article class="text">
					<h2 class="title"><span>О компании</span></h2>
					<p>
						<img alt="" src="{{tpl_url}}/images/temp/565.jpg" width="147" height="147" style="float: left; margin-right: 33px" class="avatar_round"> Ручное рубило было первым великим изобретением древнего человека, значильно облегчившим его жизнь. При помощи рубила, держа его различно, то за тупой, то за острый конец, можно было растирать и размельчать растительную пищу, соскабивать и очищать кору, раздроблять орехи, отделять корни и ветви, взрызлять землю в поисках корнеплодов, убивать мелких животных. Оно представляло из себя универсальный инструмент со множеством разнообразных функций. Одновременно с рубилом на службе человека оказались отщепы от кремня-различные острия, проколки, древнейшие скребла. Этот нехитрый инструмент возволял человеку освежевать тушу,разрезать шкуру, разделить  Одновременно с рубилом на службе человека оказались отщепы от кремня-различные острия, проколки, древнейшие скребла. Этот нехитрый инструмент возволял человеку освежевать тушу,разрезать шкуру, разделить 
					</p>
					<p>
						Ручное рубило было первым великим изобретением древнего человека, значильно облегчившим его жизнь.
					</p>
					<a href="{{home}}" class="main_btn more">Узнать больше</a>
				</article>
			</div>
		{{ callPlugin('xnews.show', { 'count' : 3, 'template' : 'xnews1'}) }}

		<section class="partners">
			<h2 class="title"><span>Наши партнеры</span></h2>
			<div id="horizontal-scrollbar-demo" class="gray-skin demo scrollable" tabindex="-1"><div class="scroll-bar vertical" style="height: 136px; display: none;"><div class="thumb" style="top: 0px; height: 139.0676691729323px;"><span></span></div></div><div class="viewport" style="height: 136px; width: 940px;"><div class="overview" style="top: 0px; left: 0px;">
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/1.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/2.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/3.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/4.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/5.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/6.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/1.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/2.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/3.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/4.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/5.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/2.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/3.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/4.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/5.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/2.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/3.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/4.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/5.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/2.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/3.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/4.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/5.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/2.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/3.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/4.png"></a>
            	<a href="{{home}}" class="partner"><img alt="" src="{{tpl_url}}/images/temp/5.png"></a>
	            <div class="clear"></div>
	        </div></div><div class="scroll-bar horizontal" style="width: 940px; display: none;"><div class="thumb" style="left: 0px; width: 1175px;"><span></span></div></div></div>
		</section>
	</div>
		
	<footer id="footer">
		<div class="footer_in">
			<div class="top">
				На нашем сайте Вы сможете найти себе компанию или просто друзей для совместного время провождения<br>Свое Спасибо можете выразить в виде материальной помощи группе создателей сайта
			</div>
			<div class="line"></div>
			<div class="copyright">
				© 2014, ВместееВеселее.рф<br>Все права защищены.
			</div>
			<div class="socials">
				Следуйте за нами:
				<div class="icons">
					<a href="{{home}}"><img alt="" src="{{tpl_url}}/images/twitter2.png"></a>
					<a href="{{home}}"><img alt="" src="{{tpl_url}}/images/twitter.png"></a>
					<a href="{{home}}"><img alt="" src="{{tpl_url}}/images/facebook.png"></a>
					<a href="{{home}}"><img alt="" src="{{tpl_url}}/images/skype.png"></a>
					<a href="{{home}}"><img alt="" src="{{tpl_url}}/images/youtube.png"></a>
				</div>
			</div>
		</div>
	</footer>
{% if not (global.flags.isLogged) %}{{ personal_menu }}{% endif %}
</body>
</html>
[debug]
{debug_queries}<br/>{debug_profiler}
[/debug]
[/TWIG]