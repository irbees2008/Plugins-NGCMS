<tr align="center" class="contRow1" >
<td><input type="image" src="{admin_url}/skins/default/images/up.gif" title="{l_menu_pro:button_up}"  onmousedown="javascript:window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=menu_pro&action=move_up&id={id}'" />&#160;<input type="image" src="{admin_url}/skins/default/images/down.gif" title="{l_menu_pro:button_down}"  onmousedown="javascript:window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=menu_pro&action=move_down&id={id}'" /></td>
<td [if_not_active]style="background-color: #cccccc"[/if_not_active]>{title}</td>
<td>{name}</td>
<td>{url}</td>
<td>{access}</td>
<td>
<input type="image" src="{admin_url}/plugins/menu_pro/tpl/images/edit.png" title="{l_menu_pro:button_edit}"  onmousedown="javascript:window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=menu_pro&action=add_form&id={id}'" />&#160;
<input type="image" src="{admin_url}/plugins/menu_pro/tpl/images/dell.png" title="{l_menu_pro:button_dell}"  onmousedown="javascript:window.location.href='{admin_url}/admin.php?mod=extra-config&plugin=menu_pro&action=dell&id={id}'" />
</td>
</tr>