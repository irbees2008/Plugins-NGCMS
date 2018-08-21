<form name="form1" method="post" action="admin.php?mod=extra-config&amp;plugin=blokmanager&amp;action=[add]menuadd_submit[/add][edit]menuedit_submit[/edit]">
[edit]
<input type="hidden" name="id" value="{id}" />[/edit]
<table border="0" width="100%" cellspacing="0" cellpadding="0">
[add]<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:id} <small>{l_blokmanager:id_d}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="5" name="id"/></td></tr>[/add]
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:menuname}<br /><small>{l_blokmanager:menuname_d}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="80" name="menuname"[edit] value="{menuname}"[/edit] /></td>
</tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:menustatic_d}</td>
<td width="50%" class="contentEntry2"><select size="10" name="menucatids[]" multiple="multiple">{catlist}</select></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:menulevel}<br /><small>{l_blokmanager:menulevel_sd}</small></td>
<td width="50%" class="contentEntry2"><input type="text" size="4" name="menulevel" [edit] value="{menulevel}"[/edit] /></td>
</tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:levelmark}<br /></td>
<td width="50%" class="contentEntry2"><TEXTAREA  NAME="levelmark" COLS="70" ROWS="2">[edit]{levelmark}[/edit]</TEXTAREA></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_blokmanager:menutemplate}<br /><small>{l_blokmanager:menutemplate_d}</small></td>
<td width="50%" class="contentEntry2"><TEXTAREA  NAME="menutemplate" COLS="80" ROWS="3">{menutemplate}</TEXTAREA></td>
</tr>
<tr>
<td width="100%" colspan="2" class="contentEdit" align="center">
<input type="submit" value="[add]{l_blokmanager:add_submit}[/add][edit]{l_blokmanager:edit_submit}[/edit]" class="button" />
</td>
</tr>
</table>
</form>
