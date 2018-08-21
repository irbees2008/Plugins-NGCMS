<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 2px;">
  <tr>
    <td width="100%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">Список соревнований</td>
  </tr>
</table>
<form action="?mod=extra-config&amp;plugin={plugin}&amp;action=tournament_delete" method="post" name="tournament">
  <table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
    <tr align="left">
      <td width="11%" class="contentHead">Дата</td>
      <td width="38%" class="contentHead">Название</td>
      <td width="12%" class="contentHead">Тип</td>
      <td width="16%" class="contentHead">Количество команд</td>
      <td width="20%" class="contentHead">Код</td>
      <td width="5%" class="contentHead"><input class="check" type="checkbox" name="master_box" title="Выбрать все" onclick="javascript:check_uncheck_all(tournament)" /></td> 
    </tr>
{entries}
    <tr>
      <td colspan="2" style="border-bottom: 1px solid #EBEBEB; padding-top: 14px; padding-bottom: 6px;">
        <a href="?mod=extra-config&amp;plugin={plugin}&amp;action=tournament_add" class="button" style="padding: 2px 6px 2px 6px; color: #333; text-decoration: none; cursor: default;">Добавить</a>
        <!--<input type="button" onmousedown="http://?mod=extra-config&amp;plugin={plugin}&amp;action=tournament_add" value="Добавить" class="button" />-->
      </td>
      <td colspan="2" align="right" style="border-bottom: 1px solid #EBEBEB; padding-top: 14px; padding-bottom: 6px;">
        <input type="submit" value="Удалить" class="button" />
      </td>
    </tr>
  </table>
</form>