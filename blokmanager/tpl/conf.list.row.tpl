<tr align="center" class="contRow1">
<td><input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/up.png" title="{l_blokmanager:button_up}"  onmousedown="javascript:window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=blokmanager&action=move_up&id={id}'" />&#160;<input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/down.png" title="{l_blokmanager:button_down}"  onmousedown="javascript:window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=blokmanager&action=move_down&id={id}'" /></td>
<td>{name}</td>
<td>{description}</td>
<td>{type}</td>
<td align="center">{online}</td>

<td>
{editbutton}&#160;{dellbutton}&#160;&#160;
<input type="image" src="{admin_url}/plugins/blokmanager/tpl/images/copy.png" title="{l_blokmanager:copy}"  onmousedown="if(window.confirm('¬ы уверены что хотите создать копию этого блока?')){ window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=blokmanager&action=copy&id={id}'; } return false;" />
</td>
</tr>
