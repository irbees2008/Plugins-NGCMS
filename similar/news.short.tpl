			[TWIG]	<!-- Article 1 start -->

                <div class="line"></div>  <!-- Dividing line -->
                
                <article id="article1"> <!-- The new article tag. The id is supplied so it can be scrolled into view. -->
                    <h2><a href="{{ news.url.full }}">{{ news.title }}</a></h2>
                    
                    <div class="line"></div>
                    
                    <div class="articleBody clear">
                    
                    	<figure> <!-- The figure tag marks data (usually an image) that is part of the article -->
	                    	<a href="{{ news.url.full }}">
		{% if (p.xfields.poster.count < 1) %}
				<img src="{{ tpl_url }}/images/no_image.jpg"  />
			{% else %}
				<img src="{{ p.xfields.poster.entries[0].purl }}" />
			{% endif %}
	</a>
                        </figure>
                        
                        <p>{{ news.short }} </p>
                    </div>
                </article>
                
				<!-- Article 1 end -->[/TWIG]
