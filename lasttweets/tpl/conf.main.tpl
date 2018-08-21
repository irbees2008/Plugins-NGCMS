<script type="text/javascript">
<!--
    function showPassword() {
		var passwd = document.getElementById('twi_password');
		var timeline = document.getElementById('timeline');
		var search = document.getElementById('search');
		
		switch(timeline.selectedIndex){
			case 0: { 
					passwd.style.display = 'none';
					search.style.display = 'none';
					break;
			}
			
			case 1: { 
					passwd.style.display = '';
					search.style.display = 'none';
					break;
			}
			
			case 2: { 
					passwd.style.display = 'none';
					search.style.display = '';
					break;
			}
		}
    }
//-->
</script>

<div style="text-align : left;">
<table class="content" border="0" cellspacing="0" cellpadding="0" align="center">	
	<tr>
		<td width="100%" colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_config_text}: lasttweets</td>	
	</tr>
	
	<tr>		
		<td>&nbsp;</td>	
	</tr>
</table>

{l_lasttweets:ads}

<form method="post" action="admin.php?mod=extra-config&amp;plugin=lasttweets&amp;action=get_pin_code">
<input type="submit" value="{l_lasttweets:access}" id="twi_password" class="button" {style_twi_password} />
</form>

<form method="post" action="admin.php?mod=extra-config&amp;plugin=lasttweets&amp;action=general_submit">

<fieldset>
<legend><b>{l_lasttweets:legend_general}</b></legend>
<table border="0" width="100%" cellspacing="0" cellpadding="0">	
	<tr>		
		<td width="50%" class="contentEntry1">{l_lasttweets:label_twi_username}</td>
		<td width="50%" class="contentEntry2"><input type="text" size="15" name="twi_username" value="{twi_username}" /></td>	
	</tr>	
	
	<tr>		
		<td width="80%" class="contentEntry1">{l_lasttweets:label_timeline}<br /><small>{l_lasttweets:descr_timeline}</small></td>
		<td width="20%" class="contentEntry2"><select name="timeline" id="timeline" onChange="showPassword()">
												<option value="0" {selected_timeline_0}>{l_lasttweets:timeline_you}</option>
												<option value="1" {selected_timeline_1}>{l_lasttweets:timeline_friends}</option>
												<option value="2" {selected_timeline_2}>{l_lasttweets:timeline_search}</option>
											 </select>
		</td>
	</tr>

	<tr id="search" {style_search}>		
		<td width="50%" class="contentEntry1">{l_lasttweets:label_search}</td>
		<td width="50%" class="contentEntry2"><input type="text" size="15" name="search" value="{search}" /></td>	
	</tr>	
	
	<tr>
		<td width="80%" class="contentEntry1">{l_lasttweets:label_GMT}<br /><small>{l_lasttweets:descr_GMT}</small></td>
		<td width="20%" class="contentEntry2"><input type="text" size="8" name="GMT" value="{GMT}" /></td>	
	</tr>
	
	<tr>
		<td width="80%" class="contentEntry1">{l_lasttweets:label_count}<br /><small>{l_lasttweets:descr_count}</small></td>
		<td width="20%" class="contentEntry2"><input type="text" size="8" name="count" value="{count}" /></td>	
	</tr>
</table>
</fieldset>

<fieldset>
<legend><b>{l_lasttweets:legend_template}</b></legend>
<table border="0" width="100%" cellspacing="0" cellpadding="0">	
	<tr>		
		<td width="80%" class="contentEntry1">{l_lasttweets:label_localsource}<br /><small>{l_lasttweets:descr_localsource}</small></td>
		<td width="20%" class="contentEntry2"><select name="localsource"><option value="0" {selected_localsource_0}>{l_lasttweets:template_site}</option><option value="1" {selected_localsource_1}>{l_lasttweets:template_plugin}</option></select></td>
	</tr>
</table>
</fieldset>

<fieldset>
<legend><b>{l_lasttweets:legend_cache}</b></legend>
<table border="0" width="100%" cellspacing="0" cellpadding="0">	
	<tr>		
		<td width="80%" class="contentEntry1">{l_lasttweets:label_cache}<br /><small>{l_lasttweets:descr_cache}</small></td>	
		<td width="20%" class="contentEntry2"><select name="cache"><option value="1" {selected_cache_1}>{l_lasttweets:cache_yes}</option><option value="0" {selected_cache_0}>{l_lasttweets:cache_no}</option></select></td>	</tr>		<tr>		<td width="80%" class="contentEntry1">{l_lasttweets:label_cacheExpire}<br /><small>{l_lasttweets:descr_cacheExpire}</small></td>		<td width="20%" class="contentEntry2"><input type="text" size="8" name="cacheExpire" value="{cacheExpire}" /></td>	
	</tr>
</table>
</fieldset>

<br />

<table border="0" width="100%" cellspacing="0" cellpadding="0">	
	<tr>		
		<td width="100%" colspan="2">&nbsp;</td>	
	</tr>
	
	<tr>		
		<td width="100%" colspan="2" class="contentEdit" align="center"><input type="submit" value="{l_lasttweets:button_save}" class="button" /></td>	
	</tr>
</table>
</form>
<br />
<b>Отблагодарить меня за работу можно через электронные кошельки:</b>
<br /><br />
<img src="{admin_url}/plugins/lasttweets/img/yandexMoney.png"> <b><span style="color: red">Я</span>ндекс.Деньги: 41001246158060</b>
<br /><br />
<img src="{admin_url}/plugins/lasttweets/img/WebMoney.png"> <b><span style="color: blue">WebMoney</span>: Z185759217217 и R128203457262</b>
<br /><br />
<b><a href="http://digitalplace.ru/">digitalplace.ru</a> &copy;</b>
</div>