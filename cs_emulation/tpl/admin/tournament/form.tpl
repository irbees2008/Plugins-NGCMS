<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 2px;">
  <tr>
    <td width="100%" class="contentHead"><a href="?mod=extra-config&amp;plugin={plugin}" style="font: bold 12px verdana, sans-serif; color: #333; text-decoration: none;"><img src="{skins_url}/images/nav.gif" hspace="8">Список соревнований</a></td>
  </tr>
</table>
<form action="?mod=extra-config&amp;plugin={plugin}&amp;action={action}&amp;id={id}" method="post" name="tournament">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="100%" colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">Создание/редактирование соревнования</td> 
    </tr> 
    <tr>
      <td class="contentEntry1"><label for="name">Название</label></td>
      <td class="contentEntry2"><input type="text" size="40" name="name" id="name" value="{name}" /></td>
    </tr>
    <tr>
      <td class="contentEntry1"><label for="type">Тип</label></td>
      <td class="contentEntry2">
        <script>
        <!--
            function switch_block()
            {
                var type = document.getElementById("type").value;
                if (type == 0)
                {
                    document.getElementById("team_count_block").style.cssText = "display: none;";
                }
                else if (type == 1 || type == 2)
                {
                    document.getElementById("team_count_block").style.removeProperty("display");
                }
            }
        -->
        </script>
        <select name="type" id="type" onchange="switch_block();" {disabled}>
{type}
        </select>
      </td>
    </tr>
    <tr>
      <td width="100%" colspan="2">
        <div id="team_count_block" {style}>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="contentEntry1"><label for="team_count">Количество команд</label></td>
              <td class="contentEntry2">
                <select name="team_count" id="team_count" {disabled}>
{team_count}
                </select>
              </td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="border-bottom: 1px solid #EBEBEB; padding-top: 14px; padding-bottom: 6px;">
        <input type="submit" name="submit" value="Отправить" class="button" />
      </td>
    </tr>
  </table>
</form>