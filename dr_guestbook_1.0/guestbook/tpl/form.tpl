<!-- PLUGIN: Guestbook -->
[textarea]
<form name="form" method="post" action="">
{bbcodes}<br />{smilies}<br />
{author}
���������: <br/><textarea name="content" style="width: 95%;" rows="8"></textarea><br/><br/>
[captcha]
����������� ���:<br/>
<input type="text" name="vcode" maxlength="5" size="30" /> <img src="{admin_url}/captcha.php" />
[/captcha]
<br/>
<input type="submit" value="���������"/>
<input type="hidden" name="ip" value="{ip}"/>
</form>
[/textarea]
<!-- END plugin: Guestbook -->
