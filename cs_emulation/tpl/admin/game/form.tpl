<form action="?mod=extra-config&amp;plugin={plugin}&amp;action={action}&amp;tid={tid}&amp;id={id}" method="post">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 2px;">
    <tr> 
      <td width="100%" colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">��������/�������������� ����</td> 
    </tr> 
    <tr>
      <td class="contentEntry1"><label for="map">�����</label></td>
      <td class="contentEntry2"><input type="text" size="40" name="map" id="map" value="{map}" /></td>
    </tr>
    <tr>
      <td class="contentEntry1"><label for="team1">������� #1</label></td>
      <td class="contentEntry2">
        <select name="team[]" id="team1">
{team1}
        </select>
      </td>
    </tr>
    <tr>
      <td class="contentEntry1"><label for="team2">������� #2</label></td>
      <td class="contentEntry2">
        <select name="team[]" id="team2">
{team2}
        </select>
      </td>
    </tr>
    <tr>
      <td class="contentEntry1"><label for="score1">��������� #1</label></td>
      <td class="contentEntry2"><input type="text" size="40" name="score[]" id="score1" value="{score1}" /></td>
    </tr>
    <tr>
      <td class="contentEntry1"><label for="score2">��������� #2</label></td>
      <td class="contentEntry2"><input type="text" size="40" name="score[]" id="score2" value="{score2}" /></td>
    </tr>
    <tr>
      <td colspan="2" style="border-bottom: 1px solid #EBEBEB; padding-top: 14px; padding-bottom: 6px;">
        <input type="submit" name="submit" value="���������" class="button" />
      </td>
    </tr>
  </table>
</form>