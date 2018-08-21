<tr>
 <td width="100%" class="contentHead" colspan="2"><img src="{admin_url}/skins/default/images/nav.gif" hspace="8" alt="" />Опрос</td>
</tr>
<tr>
 <td width="100%" class="contentEntry1">
  <table>
   <tr>
    <td>Удалить опрос:<br/></td>
    <td><input type="checkbox" name="nvdelete" /></td>
   </tr>
   <tr>
    <td>Название опроса:<br/></td>
    <td><input type="text" style="width: 300px;" name="nvtitle" value="{title}" /></td>
   </tr>
   <tr>
    <td>Ответы:<br/></td>
    <td>{answers}<br/>Новые ответы (один на строку):<br/><textarea style="height: 30px; width: 300px;" name="nvanswers" /></textarea></td>
   </tr>
    <tr>
    <td>Обновить ответы (только если изменяли или добавляли новые):<br/></td>
    <td><input type="checkbox" name="nvupdate" /></td>
   </tr>
   <tr>
    <td colspan="2">&nbsp;</td>
   </tr>
   <tr>
    <td>Обнулить результаты:</td>
    <td><input type="checkbox" name="nvrefresh" /></td>
   </tr>
   <input type="hidden" name="voteid" value="{voteid}" />
   <input type="hidden" name="mode" value="editv" />
  </table>
 </td>
</tr>
