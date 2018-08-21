<br />
<form method="post" action="{admin_url}/admin.php?mod=extra-config&amp;plugin=addnews&amp;action=generalSubmit">
<fieldset class="admGroup">
<legend><b>{l_addnews:settings}</b></legend>
<table border="0" class="content">	
	<tr>		
		<td width="80%" class="contentEntry1">{l_addnews:setting.user}<br /><small>{l_addnews:setting.user#desc}</small></td>	
		<td width="20%" class="contentEntry2">{l_addnews:setting.statusTitle}<br />
        	<input type="checkbox" name="perm[user][]" value="0" class="check"{ch_user_0} />
        	<input type="checkbox" name="perm[user][]" value="4" class="check"{ch_user_4} />
        	<input type="checkbox" name="perm[user][]" value="3" class="check"{ch_user_3} />
        	<input type="checkbox" name="perm[user][]" value="2" class="check"{ch_user_2} />
        	<input type="checkbox" name="perm[user][]" value="1" class="check"{ch_user_1} />                                                
		</td>
	</tr>
	<tr>		
		<td width="80%" class="contentEntry1">{l_addnews:setting.approve}<br /><small>{l_addnews:setting.approve#desc}</small></td>	
		<td width="20%" class="contentEntry2">{l_addnews:setting.statusTitle}<br />
        	<input type="checkbox" name="perm[approve][]" value="0" class="check"{ch_approve_0} />
        	<input type="checkbox" name="perm[approve][]" value="4" class="check"{ch_approve_4} />
        	<input type="checkbox" name="perm[approve][]" value="3" class="check"{ch_approve_3} />
        	<input type="checkbox" name="perm[approve][]" value="2" class="check"{ch_approve_2} />
        	<input type="checkbox" name="perm[approve][]" value="1" class="check"{ch_approve_1} />                                                
		</td>
	</tr>
	<tr>		
		<td width="80%" class="contentEntry1">{l_addnews:setting.mainpage}<br /><small>{l_addnews:setting.mainpage#desc}</small></td>	
		<td width="20%" class="contentEntry2">{l_addnews:setting.statusTitle}<br />
        	<input type="checkbox" name="perm[mainpage][]" value="0" class="check"{ch_mainpage_0} />
        	<input type="checkbox" name="perm[mainpage][]" value="4" class="check"{ch_mainpage_4} />
        	<input type="checkbox" name="perm[mainpage][]" value="3" class="check"{ch_mainpage_3} />
        	<input type="checkbox" name="perm[mainpage][]" value="2" class="check"{ch_mainpage_2} />
        	<input type="checkbox" name="perm[mainpage][]" value="1" class="check"{ch_mainpage_1} />                                                
		</td>
	</tr>
	<tr>		
		<td width="80%" class="contentEntry1">{l_addnews:setting.meta}<br /><small>{l_addnews:setting.meta#desc}</small></td>	
		<td width="20%" class="contentEntry2">{l_addnews:setting.statusTitle}<br />
        	<input type="checkbox" name="perm[meta][]" value="0" class="check"{ch_meta_0} />
        	<input type="checkbox" name="perm[meta][]" value="4" class="check"{ch_meta_4} />
        	<input type="checkbox" name="perm[meta][]" value="3" class="check"{ch_meta_3} />
        	<input type="checkbox" name="perm[meta][]" value="2" class="check"{ch_meta_2} />
        	<input type="checkbox" name="perm[meta][]" value="1" class="check"{ch_meta_1} />                                                
		</td>
	</tr>
	<tr>		
		<td width="80%" class="contentEntry1">{l_addnews:setting.categories}<br /><small>{l_addnews:setting.categories#desc}</small></td>	
		<td width="20%" class="contentEntry2">{l_addnews:setting.statusTitle}<br />
        	<input type="checkbox" name="perm[categories][]" value="0" class="check"{ch_categories_0} />
        	<input type="checkbox" name="perm[categories][]" value="4" class="check"{ch_categories_4} />
        	<input type="checkbox" name="perm[categories][]" value="3" class="check"{ch_categories_3} />
        	<input type="checkbox" name="perm[categories][]" value="2" class="check"{ch_categories_2} />
        	<input type="checkbox" name="perm[categories][]" value="1" class="check"{ch_categories_1} />                                                
		</td>
	</tr>
	<tr>		
		<td width="80%" class="contentEntry1">{l_addnews:setting.altname}<br /><small>{l_addnews:setting.altname#desc}</small></td>	
		<td width="20%" class="contentEntry2">{l_addnews:setting.statusTitle}<br />
        	<input type="checkbox" name="perm[altname][]" value="0" class="check"{ch_altname_0} />
        	<input type="checkbox" name="perm[altname][]" value="4" class="check"{ch_altname_4} />
        	<input type="checkbox" name="perm[altname][]" value="3" class="check"{ch_altname_3} />
        	<input type="checkbox" name="perm[altname][]" value="2" class="check"{ch_altname_2} />
        	<input type="checkbox" name="perm[altname][]" value="1" class="check"{ch_altname_1} />                                                
		</td>
	</tr>
	<tr>
		<td width="80%" class="contentEntry1">{l_addnews:setting.captcha}<br /><small>{l_addnews:setting.captcha#desc}</small></td>
		<td width="20%" class="contentEntry2">{l_addnews:setting.statusTitle}<br />
        	<input type="checkbox" name="perm[captcha][]" value="0" class="check"{ch_captcha_0} />
        	<input type="checkbox" name="perm[captcha][]" value="4" class="check"{ch_captcha_4} />
        	<input type="checkbox" name="perm[captcha][]" value="3" class="check"{ch_captcha_3} />
        	<input type="checkbox" name="perm[captcha][]" value="2" class="check"{ch_captcha_2} />
        	<input type="checkbox" name="perm[captcha][]" value="1" class="check"{ch_captcha_1} />                                                
		</td>
	</tr>
</table>
</fieldset>
<fieldset class="admGroup">
<legend><b>{l_addnews:display}</b></legend>
<table border="0" class="content">	
	<tr>		
		<td width="80%" class="contentEntry1">{l_addnews:localsource}<br /><small>{l_addnews:localsource#desc}</small></td>
		<td width="20%" class="contentEntry2">{localsource}</td>
	</tr>
	<tr>		
		<td width="80%" class="contentEntry1">{l_addnews:skin}<br /><small>{l_addnews:skin#desc}</small></td>
		<td width="20%" class="contentEntry2">{skin}</td>
	</tr>
</table>
</fieldset>
<table border="0" class="content">
<tr><td width="100%" colspan="2">&nbsp;</td></tr>
<tr>
<td width="100%" colspan="2" class="contentEdit" align="center">
<input type="submit" value="{l_addnews:buttonSave}" class="button" />
</td>
</tr>
</table>
</form>