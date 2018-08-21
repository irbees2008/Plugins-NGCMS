<article class="full-post">
	<div class="titlef">
      {{announce_name}}
	</div><br/>
				<section class="profile-section">
			  <div class="profile-avatar">
				<div class="profile-avatar-inner">
				  <img src="{{avatar}}" class="avatar_round" alt=""><br/>
				  <a href="{{author_link}}" class="name">{{uname}}</a><br/>
				  <span>{{uage}} {{wordage}}</span>, <span>{{ucity}}</span>
				</div>
			  </div>
			  <div class="profile-content">
				<div class="profile-name">{{announce_place}}</div>
				<div class="profile-info">
					<div class="event_info">
						<span class="time">{{date|date('Y-m-d h:i:s')}}</span><br>
						<span class="place"></span>
					</div>
				<p>{{announce_description}}</p>
				<div class="post-full-meta">Просмотров: {{ views }}</div>
				</div>
			  </div>
			</section>

</article>
