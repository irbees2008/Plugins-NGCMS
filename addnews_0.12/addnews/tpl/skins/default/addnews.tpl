<script type="text/javascript">
[edit.split]var currentInputAreaID = 'ng_news_content_short';[/edit.split][edit.nosplit]var currentInputAreaID = 'ng_news_content';[/edit.nosplit]
function changeActive(name) {
	if (name == 'full') {
    	document.getElementById('container.content.full').className  = 'contentActive';
	    document.getElementById('container.content.short').className = 'contentInactive';
    	currentInputAreaID = 'ng_news_content_full';
	} else {
    	document.getElementById('container.content.short').className = 'contentActive';
	    document.getElementById('container.content.full').className  = 'contentInactive';
    	currentInputAreaID = 'ng_news_content_short';
	}
}
</script>
<form method="post" name="form" action="{php_self}?action=doAdd">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_40.gif" width="7" height="36" /></td>
				<td style="background-image:url('{tpl_url}/images/2z_41.gif');" width="100%">&nbsp;<b><font color="#FFFFFF">{l_addnews:header.title}</font></b></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_44.gif" width="7" height="36" /></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td style="background-image:url('{tpl_url}/images/2z_54.gif');" width="7">&nbsp;</td>
				<td bgcolor="#FFFFFF">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="200" style="padding-left: 15px;">{l_addnews:title}</td>
						<td style="padding: 5px;"><input type="text" size="30" name="title" value="" /></td>
					</tr>
					[altname]
					<tr>
						<td width="200" style="padding-left: 15px;">{l_addnews:altname}</td>
						<td style="padding: 5px;"><input type="text" size="30" name="alt_name" value="" /></td>
					</tr>
					[/altname]
                    [categories]
                    <tr>
						<td width="200" style="padding-left: 15px;">{l_addnews:category}</td>
						<td style="padding: 5px;">{categories}</td>
					</tr>
                    [/categories]
					<tr>
						<td width="200" valign="top" style="padding-left: 5px;"><br />
							<a href="javascript:ShowOrHide('bbcodes');"><img src="{tpl_url}/images/arr_bot.gif" border="0" />{l_addnews:bbcodes}</a><br />
							<div id="bbcodes" style="display : none;"><br />{quicktags}</div></td>
						<td valign="top"><br />
							<a href="javascript:ShowOrHide('smilies');"><img src="{tpl_url}/images/arr_bot.gif" border="0" />{l_addnews:smilies}</a><br />
							<div id="smilies" style="display : none;"><br />{smilies}</div></td>
					</tr>
					<tr>
						<td colspan="2" style="padding: 15px;">
                            [edit.split]
							{l_addnews:split.short}<br />
					    	<div id="container.content.short" class="contentActive"><textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('short');" onfocus="changeActive('short');" name="ng_news_content_short" id="ng_news_content_short" rows="10" tabindex="2"></textarea></div>
							{l_addnews:split.full}<br />
							<div id="container.content.full" class="contentInactive"><textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('full');" onfocus="changeActive('full');" name="ng_news_content_full" id="ng_news_content_full" rows="10" tabindex="2"></textarea></div>
							[/edit.split]
							[edit.nosplit]
							{l_addnews:nosplit}
						    <div id="container.content" class="contentActive"><textarea style="width: 99%; padding: 1px; margin: 1px;" name="ng_news_content" id="ng_news_content" rows="10" tabindex="2"></textarea></div>
							[/edit.nosplit]
						</td>
					</tr>
					[meta]
					<tr>
						<td width="200" style="padding-left: 15px;">{l_addnews:description}</td>
						<td style="padding: 5px;"><input type="text" size="30" name="description" value="" /></td>
					</tr>
					<tr>
						<td width="200" style="padding-left: 15px;">{l_addnews:keywords}</td>
						<td style="padding: 5px;"><input type="text" size="30" name="keywords" value="" /></td>
					</tr>
					[/meta]
					<tr>
						<td style="padding-left: 15px;"><label><input type="checkbox" name="approve" value="1" class="check" id="approve"{flag_approve} /> {l_addnews:approve}</label></td>
						<td style="padding-left: 5px;"><label><input type="checkbox" name="mainpage" value="1" class="check" id="mainpage"{flag_mainpage} /> {l_addnews:mainpage}</label></td>
					</tr>
                    [not-logged]
					<tr>
						<td width="200" style="padding-left: 15px;">{l_addnews:name}</td>
						<td style="padding: 5px;"><input type="text" size="30" name="name" value="{savedname}"  /></td>
					</tr>
					<tr>
						<td style="padding-left: 15px;">{l_addnews:password} <small>{l_addnews:ifreg}</small></td>
						<td style="padding: 5px;"><input class="password" type="password" maxlength="16" size="30" name="password" value="" /></td>
					</tr>	
					[/not-logged]
					[captcha]
                    <tr>
						<td style="padding-left: 15px;"><img id="img_captcha" onclick="reload_captcha();" src="{captcha_url}&rand={rand}" alt="captcha" /></td>
						<td style="padding: 5px;"><input class="important" type="text" name="vcode" maxlength="5" size="30" /></td>
					</tr>
					[/captcha]
					<tr>
						<td style="padding: 15px;" align="left" colspan="2"><input type="submit" class="button" value="{l_addnews:add}"/>&nbsp; <input type="reset" class="button" value="{l_addnews:clear}" /></td>
					</tr>
				</table>
				</td>
				<td style="background-image:url('{tpl_url}/images/2z_59.gif');" width="7">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_68.gif" width="7" height="4" /></td>
				<td style="background-image:url('{tpl_url}/images/2z_69.gif');" width="100%"></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_70.gif" width="7" height="4" /></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>