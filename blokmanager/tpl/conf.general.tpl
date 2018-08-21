<fieldset>
<legend><b>{l_blokmanager:general_legend}</b></legend>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="100%" class="contentEntry1" align="center">
<input type="button" onmousedown="javascript:window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=blokmanager&action=clear_cash'" value="{l_blokmanager:button_clear_cash}" class="navbutton" />
</td>
</tr>
</table>
</fieldset>
[locationlist]
<fieldset>
<legend><b>{l_blokmanager:general_bloklocation}</b></legend>
<form action="?mod=extra-config&plugin=blokmanager&action=save_bloklocation" method="post">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentEntry1" align="left">{l_blokmanager:general_bloklocation_desc}
</td>
<td width="50%" class="contentEntry1" align="left"><textarea name="blocation" cols="70" rows="10">{locationlist}</textarea></td>
</tr>
<tr>
<td width="50%" class="contentEntry1" align="left">{l_blokmanager:general_onoffckeditor_desc}
</td>
<td width="50%" class="contentEntry1" align="left">{ñkonoffos}</td>
</tr>
<tr>
<td width="100%" class="contentEntry1" align="center" colspan="2">
<input type="submit" value="{l_blokmanager:button_setlocation}" class="navbutton" />
</td>
</tr>
</table>
</form>
</fieldset>
<fieldset>
<legend><b>{l_blokmanager:general_deftemplates}</b></legend>
<form action="?mod=extra-config&plugin=blokmanager&action=save_deftemplates" method="post">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentEntry1" align="left">{l_blokmanager:general_defblokouter}
</td>
<td width="50%" class="contentEntry1" align="left"><textarea name="defblokouter" cols="70" rows="10">{defblokouter}</textarea></td>
</tr>
<tr>
<td width="50%" class="contentEntry1" align="left">{l_blokmanager:general_defmenurow}
</td>
<td width="50%" class="contentEntry1" align="left"><textarea name="defmenurow" cols="70" rows="10">{defmenurow}</textarea></td>
</tr>
<tr>
<td width="100%" class="contentEntry1" align="center" colspan="2">
<input type="submit" value="{l_blokmanager:button_setlocation}" class="navbutton" />
</td>
</tr>
</table></form>
</fieldset>
[/locationlist]
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td width="100%" colspan="2">&nbsp;</td></tr>
<tr>
<td width="100%" colspan="2" class="contentEdit" align="center">
&nbsp;
</td>
</tr>
</table>
