[TWIG]
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{ lang['langcode'] }}" lang="{{ lang['langcode'] }}" dir="ltr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset={{ lang['encoding'] }}" />
		<meta http-equiv="content-language" content="{{ lang['langcode'] }}" />
		<meta name="generator" content="{{ what }} {{ version }}" />
		<meta name="document-state" content="dynamic" />
		{{ htmlvars }}
		<link rel="stylesheet" href="{{ tpl_url }}/css/reset.css">
		<link rel="stylesheet" href="{{ tpl_url }}/css/mangguo.css">
	<!--	<link rel="stylesheet" href="{{ tpl_url }}/css/style0.css"> -->
		
		
		{% if pluginIsActive('rss_export') %}<link href="{{ home }}/rss.xml" rel="alternate" type="application/rss+xml" title="RSS" />{% endif %}
		<script src="{{ tpl_url }}/js/jquery.js"></script>
		<script type="text/javascript" src="{{ scriptLibrary }}/functions.js"></script>
		<script type="text/javascript" src="{{ scriptLibrary }}/ajax.js"></script>
		<script type="text/javascript" src="{{tpl_url}}/js/jquery.min.js"></script>
		<title>{{ titles }}</title>
	</head>
	<body>
		<div id="loading-layer"><img src="{{ tpl_url }}/img/loading.gif" alt="" /></div>
	
	<div class="page">
<div class="header">
	<h1 class="logo"><a href="{{home}}">{{ home_title }}</a></h1>
	<ul class="quick-menu clearfix">
		<!-- <li><span>RSS</span></li> -->
		{% if not (global.flags.isLogged) %}
		<li class="subscribe"><a href="{{ home }}/login/">Вход</a></li>
		<li class="random"><a href="{{ home }}/register/">Регистрация</a></li>
		{% else %}
		<li class="random"><a href="{{ home }}/logout/">Выход</a></li>
		{% endif %}
	</ul>
</div>
<div class="content">
{% if pluginIsActive('zboard') %}
	<div id="slide" class="slide">
		<ol class="slide-content">
		{{ callPlugin('zboard.show', {'number' : 3, 'mode' : 'rnd', 'template': 'block_zboard'}) }}
		</ol>

		<span class="prev"></span>
		<span class="next"></span>
	</div>
	<!--
	<div id="slide-toggle" class="slide-toggle clearfix">
		<span class="toggle">???????</span>
	</div>
	--><br/>

	<div class="search">
		<div class="message">Все полимеры просраны! <a href="http://ngcms.ru/forum/viewtopic.php?id=3412" target="_blank">>>></a></div>
		<form action="{{home}}/zboard/search/" method="get" class="searchform clearfix">
			<span class="s"><input type="text" name="keywords" id="keywords" value=""></span><span class="searchsubmit"><button type="submit" name="submit"></button></span>
		</form>
	</div>

	<div class="tab">
		<ul class="clearfix">
<li class="menu-item menu-item-type-custom menu-item-object-custom {% if isHandler('news') %}current-menu-item{% endif %}"><a href="{{home}}">Главная</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page {% if isHandler('zboard') and not isHandler('zboard:list') and not ( isHandler('zboard:send') or isHandler('zboard:edit') ) %}current-menu-item{% endif %}"><a href="{{home}}/plugin/zboard/">Доска объявлений</a></li>
<li class="menu-item menu-item-type-custom menu-item-object-custom {% if isHandler('zboard:send') %}current-menu-item{% endif %}"><a href="{{home}}/plugin/zboard/send/">Добавить объявление</a></li>
{% if (global.flags.isLogged) %}
<li class="menu-item menu-item-type-post_type menu-item-object-page {% if (global.flags.isLogged) and ( isHandler('zboard:list') or isHandler('zboard:edit') ) %}current-menu-item{% endif %}"><a href="{{home}}/plugin/zboard/list/">Список моих объявлений</a></li>
{% endif %}
		</ul>
	</div>
	
	<div class="quick-link">

		<div class="clearfix">
			<div class="counter">Всего доступно объявлений <a href="{{home}}/plugin/zboard/"><b>{{ callPlugin('zboard.show_entries_cnt') }}</b></a></div>
		</div>
	</div>
{% endif %}

	<div class="grid-m0s5 clearfix">
	
	
		<div class="col-main">
			<div class="main-wrap">
				{{mainblock}}
			</div>
		</div>

		
	<div class="col-sub">
		<div class="sidebar">
		
		{% if isHandler('news') %}
			<div class="random-post">
				<div class="hd">Категории</div>
				<div class="bd">
				<ul class="clearfix">
					{categories}
				</ul>
				</div>
			</div>
		{% endif %}
		
		{% if pluginIsActive('zboard') and isHandler('zboard') %}
			<div class="random-post">
				<div class="hd">Категории объявлений</div>
				<div class="bd">
					{{ callPlugin('zboard.show_catz_tree') }}
				</div>
			</div>
		
			<div class="latest-comment">
				<div class="hd">Последние добавленные</div>
				<div class="bd">
					<ul class="clearfix">
					{{ callPlugin('zboard.show', {'number' : 10, 'mode' : 'last', 'template': 'block_zboard_last'}) }}
					</ul>
				</div>
			</div>
			
			
			<div class="latest-comment">
				<div class="hd">Самые просматриваемые</div>
				<div class="bd">
					<ul class="clearfix">
					{{ callPlugin('zboard.show', {'number' : 10, 'mode' : 'view', 'template': 'block_zboard_views'}) }}
					</ul>
				</div>
			</div>
		{% endif %}
		
		</div>
	</div>
		

	</div>
</div>


<div class="footer">
	<div class="copyright">COPYRIGHT &copy; 2010-{{ now|date("Y") }} <a href="http://ngcms.ru">NG CMS</a> - THEME BY <a target="_blank" href="http://www.mangguo.org/">MANGGUO.ORG</a></div>
	<div class="about clearfix">
		<ul class="sitemap clearfix">
<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="{{home}}">Главная</a></li>
<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="{{home}}/plugin/zboard/">Доска объявлений</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="{{home}}/plugin/zboard/send/">Добавить объявление</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="{{home}}/rss.xml">RSS</a></li>
		</ul>
		<a href="http://rostunov.com/" class="licence" title="Сайт сделан в студии Пушистых котов. За копирование ссым в тапки.">&copy; rostunov.com</a>
	</div>
</div>
</div>
<!-- <script src="{{ tpl_url }}/js/jquery.min.js"></script> -->
<script src="{{ tpl_url }}/js/jquery.cookie.js"></script>
<script src="{{ tpl_url }}/js/mangguo.js"></script>

	</body>
</html>
[/TWIG]