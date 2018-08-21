<tr align="center" class="contRow1">
<td>{id}</td>
<td>{menuname}</td>
<td><input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/edit.png" title="{l_blokmanager:button_edit}"  onmousedown="javascript:window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=blokmanager&action=menuedit&id={id}'" />&#160;
<input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/dell.png" title="{l_blokmanager:button_dell}"  onmousedown="if(window.confirm('¬ы уверены что хотите удалить это меню?')){ window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=blokmanager&action=menudell&id={id}'; } return false;" />&#160;&#160;
<input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/copy.png" title="{l_blokmanager:menucopy}"  onmousedown="if(window.confirm('¬ы уверены что хотите создать копию этого меню?')){ window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=blokmanager&action=menucopy&id={id}';}return false;" />
</td>
</tr>
