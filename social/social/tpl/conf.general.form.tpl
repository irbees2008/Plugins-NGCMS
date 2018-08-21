<br />
<form method="post" action="admin.php?mod=extra-config&amp;plugin=social&amp;action=generalSubmit">
<fieldset>
<legend><b>{l_social:bitly.integration}</b></legend>
<table border="0" width="100%" cellspacing="0" cellpadding="0">	
	<tr>		
		<td width="80%" class="contentEntry1">{l_social:bitly.integration}<br /><small>{l_social:bitly.integration#desc}</small></td>	
		<td width="20%" class="contentEntry2">{cache}</td>
	</tr>
	<tr>
		<td width="80%" class="contentEntry1">{l_social:bitly.login}<br /><small>{l_social:bitly.login#desc}</small></td>
		<td width="20%" class="contentEntry2"><input type="text" size="35" name="login" value="{login}" /></td>	
	</tr>
	<tr>
		<td width="80%" class="contentEntry1">{l_social:bitly.key}<br /><small>{l_social:bitly.key#desc}</small></td>
		<td width="20%" class="contentEntry2"><input type="text" size="35" name="api_key" value="{api_key}" /></td>	
	</tr>
</table>
</fieldset>
<fieldset>
<legend><b>{l_social:display}</b></legend>
<table border="0" width="100%" cellspacing="0" cellpadding="0">	
	<tr>		
		<td width="80%" class="contentEntry1">{l_social:localsource}<br /><small>{l_social:localsource#desc}</small></td>
		<td width="20%" class="contentEntry2">{localsource}</td>
	</tr>
	<tr>		
		<td width="80%" class="contentEntry1">{l_social:skin}<br /><small>{l_social:skin#desc}</small></td>
		<td width="20%" class="contentEntry2">{skin}</td>
	</tr>
</table>
</fieldset>
<fieldset>
<legend><b>{l_social:cache}</b></legend>
<table border="0" width="100%" cellspacing="0" cellpadding="0">	
	<tr>		
		<td width="80%" class="contentEntry1">{l_social:cache}<br /><small>{l_social:cache#desc}</small></td>	
		<td width="20%" class="contentEntry2">{cache}</td>
	</tr>
	<tr>
		<td width="80%" class="contentEntry1">{l_social:cacheExpire}<br /><small>{l_social:cacheExpire#desc}</small></td>
		<td width="20%" class="contentEntry2"><input type="text" size="25" name="cacheExpire" value="{cacheExpire}" /></td>	
	</tr>
</table>
</fieldset>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td width="100%" colspan="2">&nbsp;</td></tr>
<tr>
<td width="100%" colspan="2" class="contentEdit" align="center">
<input type="button" onmousedown="javascript:window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=social&action=clearCache'" value="{l_social:buttonClearCache}" class="button" />&nbsp
<input type="submit" value="{l_social:buttonSave}" class="button" />
</td>
</tr>
</table>
</form>