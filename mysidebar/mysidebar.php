<?php
if (!defined('NGCMS')) die ('HAL');
add_act('index', 'mysidebar');

	function mysidebar(){
		global $tvars, $template, $tpl;

	$ref = $_SERVER['REQUEST_URI'];
	$ref = explode("/", $ref);
	$url_id = $ref[2];
$url_id = substr($url_id, 0, -5);	

if ($url_id == 'poisk-po-metro' or $url_id == 'adssearch'){
$side_show = '';
}else{
$side_show = '
<span class="r_title">� ���</span>
<ul>
	<li><a href="http://iqir.ru/static/kompaniya.html">��������</a></li>
	<li><a href="http://iqir.ru/static/partnery.html">��������</a></li>
	<li><a href="http://iqir.ru/static/proekty.html">�������</a></li>
	<li><a href="http://iqir.ru/static/komanda.html">�������</a></li> 
	<li><a href="http://iqir.ru/static/istoriya.html">�������</a></li> 
	<li><a href="http://iqir.ru/novosti.html">�������</a></li> 
	<li><a href="http://iqir.ru/static/vakansii.html">��������</a></li> 
</ul>
<span class="r_title">�������</span>
<ul>
	<li><a href="http://iqir.ru/static/poisk-po-metro.html">������ � ��</a></li>
	<li><a href="http://iqir.ru/static/nedvizhimost-v-regionah.html">������� ��</a></li>
	<li><a href="http://iqir.ru/static/nedvizhimost-za-rubezhom.html">�� �������</a></li>
</ul>
<span class="r_title">������</span>
<ul>
	<li><a href="http://iqir.ru/static/uslugi-investoram.html">����������</a></li> 
	<li><a href="http://iqir.ru/static/uslugi-vladelcam.html">����������</a></li> 
	<li><a href="http://iqir.ru/static/uslugi-korporativnym-klientam.html">������������� ��������</a></li>
	<li><a href="http://iqir.ru/static/uslugi-chastnym-klientam.html">������� ��������</a></li> 
	<li><a href="http://iqir.ru/static/uslugi-rieltoram-i-develouperam.html">��������� � �����������</a></li> 
</ul> 	
<span class="r_title">����. �����������</span>
<ul>
	<li><a href="http://iqir.ru/sp-chastnym-licam.html">������� �����</a></li> 
	<li><a href="http://iqir.ru/sp-organizaciyam.html">������������</a></li> 
</ul>
<span class="r_title">��������</span>
<ul>
	<li><a href="http://iqir.ru/plugin/feedback/?id=3">������</a></li> 
	<li><a href="http://iqir.ru/plugin/feedback/?id=4">�-���������</a></li> 
	<li><a href="http://iqir.ru/plugin/feedback/?id=5">������</a></li>
	<li><a href="http://iqir.ru/plugin/feedback/?id=6">������</a></li> 
</ul>
';
}


		$tvars['vars'] = array	(
				'my_data'	=>	$side_show
								);

			$tpl -> template('mysidebar', extras_dir."/mysidebar/tpl");
			$tpl -> vars('mysidebar', $tvars);
			$output .= $tpl -> show('mysidebar');
			$template['vars']['data_mysidebar'] = $output;

}