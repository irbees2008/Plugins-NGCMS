{% if (entries) %}
<section class="questions">
	<h2 class="title">
		<span>������ / �����</span>
	</h2>
{% for entry in entries %}
	<div class="question_item">
		<div class="question">{{entry.question}}</div>
		<div class="answer">{{entry.answer}}</div>
	</div>
	<div class="line"></div>
{% endfor %}
<a href="{{home}}/plugin/faq/" class="main_btn">��� �������</a>				
</section>
{% endif %}



								