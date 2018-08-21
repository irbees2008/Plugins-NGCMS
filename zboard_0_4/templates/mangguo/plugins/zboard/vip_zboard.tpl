{% if (error) %}
<div class="feed-me">
{{error}}
</div>
{% endif %}

<div class="comment">
<h3><span>Оплата VIP объявления</span></h3>
<form method="post" action="{{pay_url}}" class="comment-form" name="form">
<input type="hidden" name="zid" value="{{zid}}">
<ul class="comment-author">
<li class="item clearfix">
    <select name="price_time_id">
        <option disabled>Выберите время</option>
        {% for entry in entriesPrices %}
            <option value="{{entry.id}}">{{entry.time}} д. - {{entry.price}} руб.</option>
        {% endfor %}
    </select>
</li>

</ul>
<span class="submit"><button name="submit" type="submit"  tabindex="5" onclick="javascript:$('#file_upload').uploadifive('upload')" >Отправить</button></span>
</form>
</div>