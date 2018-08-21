
				<img src="http://ngcmshak.ru/templates/vektor/images/list_w.png">Всего на сайте: <b>{{all}} </b><br />
				<img src="http://ngcmshak.ru/templates/vektor/images/list_w.png">Анонимов: {{num_guest}}<br />
				<img src="http://ngcmshak.ru/templates/vektor/images/list_w.png">Авторизированных: {{num_auth}}<br />
				<i><img src="http://ngcmshak.ru/templates/vektor/images/list_w.png">Команда сайта:</i> {{num_team}}<br />
				<i><img src="http://ngcmshak.ru/templates/vektor/images/list_w.png"> Пользователи:</i> {{num_users}}<br />
				<img src="http://ngcmshak.ru/templates/vektor/images/list_w.png"> Поисковых роботов: {{num_bot}}
				{% if (entries_team.true) %}
				<br /><br />
				<img src="http://ngcmshak.ru/templates/vektor/images/list_w.png">{{entries_team.print}}
				{% endif %}
				{% if (entries_user.true) %}
				<br /><br />
				<img src="http://ngcmshak.ru/templates/vektor/images/list_w.png">{{entries_user.print}}
				{% endif %}
				{% if (today.true) %}
				<br /><br />
				<img src="http://ngcmshak.ru/templates/vektor/images/list_w.png">{{today.print}}
				{% endif %}
				{% if (entries_bot.true) %}
				<br /><br />
				<img src="http://ngcmshak.ru/templates/vektor/images/list_w.png">{{entries_bot.print}}
				{% endif %}
				