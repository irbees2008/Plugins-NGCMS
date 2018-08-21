{% if (submit) %}

<div class="t-side">
<div class="hd">Поиск</div>
<div class="bd">

<form id="search" method="post" action=""> 
<label class="conl">Ключевые слова<br />
<input type="text" name="keywords" size="40" maxlength="100" />
<br />
</label> 

<label class="conl">Категории<br />
<select id="cat" name="cat_id"> 
<option value='0'>Все имеющиеся</option>
{{ options }}
</select> 
<br />
</label> 

<label class="conl">Поиск в<br />
<select name="search_in"> 
<option value='all' selected>В тексте объявления и заголовке</option>
<option value='text'>Только в тексте объявления</option>
<option value='title'>Только в заголовке</option>
</select> 
<br />
</label>
<br /><br /><br />

<input type="submit" name="submit" value="Отправить" accesskey="s" />
</form> 

</div>
</div>
{% else %}

<div class="t-side">
<div class="hd">Результаты поиска</div>
<div class="bd">

<table class="hosting">
				<tr>
					<th>Дата</th>
					<th>Категория</th>
					<th>Заголовок</th>
					<th>Объявление</th>
					<th>Автор</th>
				</tr>
				{% for entry in entries %}
				<tr>
					<td class="website">
						<a>{{entry.date|date("d-m-Y H:i")}}</a>
					</td>
					<!-- <th class="rank">1</th> -->
					<td class="price">
						<a href="{{entry.catlink}}" class="tag-{{entry.cid}}">{{entry.cat_name}}</a>
					</td>

					<td class="disk-space">
						<a href="{{ entry.fulllink }}">{{ entry.announce_name }}</a>
					</td>
					<td class="bandwidth">
						{{entry.announce_description|truncateHTML(30,'...')}}
					</td>
					<td class="domain">
						<a href="{{entry.ulink}}">{{entry.author}}</a>
					</td>
				</tr>
				{% else %}
				<tr>
					<td colspan="6" class="website">
						По вашему запросу <b>{{get_url}}</b> ничего не найдено
					</td>
				</tr>
				{% endfor %}
				<tr>
					<td colspan="6" class="website">
						<a href='{{home}}/plugin/events/search/'>Вернуться назад</a>
					</td>
				</tr>
				{% if (pages.true) %}
				<tr>
					<th colspan="6"><div class="pagenavi clearfix">{% if (prevlink.true) %}{{ prevlink.link }}{% endif %}{{ pages.print }}{% if (nextlink.true) %}{{ nextlink.link }}{% endif %}</div></th>
				</tr>
				{% endif %}
</table>

</div>
</div>
{% endif %}