<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 2px;">
  <tr>
    <td width="100%" colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">Список команд</td>
  </tr>
</table>
<form action="?mod=extra-config&amp;plugin={plugin}&amp;action=team_delete&amp;tid={tid}" method="post" name="team">
  <table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
{entries}
    <tr>
      <td colspan="2" style="border-bottom: 1px solid #EBEBEB; padding-top: 14px; padding-bottom: 6px;">
        <a href="?mod=extra-config&amp;plugin={plugin}&amp;action=team_add&amp;tid={tid}" class="button" style="padding: 2px 6px 2px 6px; color: #333; text-decoration: none; cursor: default;">Добавить</a>
      </td>
      <td colspan="2" align="right" style="border-bottom: 1px solid #EBEBEB; padding-top: 14px; padding-bottom: 6px;">
        <input type="submit" value="Удалить" class="button" />
      </td>
    </tr>
  </table>
</form>
