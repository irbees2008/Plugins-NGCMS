<form action="?mod=extra-config&amp;plugin={plugin}&amp;action={action}&amp;tid={tid}&amp;id={id}" method="post">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 2px;">
    <tr> 
      <td width="100%" colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">Создание/редактирование команды</td> 
    </tr> 
    <tr>
      <td class="contentEntry1"><label for="name">Название</label></td>
      <td class="contentEntry2"><input type="text" size="40" name="name" id="name" value="{name}" /></td>
    </tr>
    <tr>
      <td class="contentEntry1"><label for="group">Группа</label></td>
      <td class="contentEntry2">
        <select name="group" id="group">
{groups}
        </select>
      </td>
    </tr>
    <tr>
      <td class="contentEntry1"><label for="flag">Флаг</label></td>
      <td class="contentEntry2">
        <select name="flag" id="flag">
{flags}
        </select>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="border-bottom: 1px solid #EBEBEB; padding-top: 14px; padding-bottom: 6px;">
        <input type="submit" name="submit" value="Отправить" class="button" />
      </td>
    </tr>
  </table>
</form>