<div class="article-masonry-container">
	<article class="article-masonry-box">
		<div id="post-<?php the_ID(); ?>" <?php post_class('article-masonry-wrapper'); ?>>
			<?php if(!post_password_required()) : ?>


				<?php
		if ( '' != get_the_post_thumbnail() ) {
			echo '<div class="entry-featuredImg"><a href="' .esc_url(get_permalink()). '"><span class="overlay-img"></span>';
			if ($i == 0 && $lastbig == 1 ) {
				the_post_thumbnail('annina-normal-post');
			} else {
				the_post_thumbnail('annina-masonry-post');
			}
			echo '</a></div>';
		}
	?>
				

			<?php endif; ?>

			<h2><a href="<?php echo get_permalink() ?>" title="Жми"><?php echo get_the_title(); ?></a></h2>
			<?php if(ot_get_option('hide_top_meta', null, JEG_PAGE_ID) !== "on" && !post_password_required() ) { ?>
			<div class="clearfix article-meta">
				<?php if ( is_sticky() && ! is_paged() ) { ?>
					<div class="featured-post">Sticky</div>
				<?php } else { ?>
					<a href="<?php echo get_permalink() ?>"><?php echo get_the_date(); ?></a>
				<?php } ?>
			</div>
			<?php } ?>

			<?php $excerpt = get_the_excerpt(); if ( $excerpt != '' ) : ?>
			<div class="article-masonry-summary">
				<p><?php echo $excerpt ?></p>
			</div>
			<?php endif; ?>

			<?php
				if(ot_get_option('hide_bottom_meta', null, JEG_PAGE_ID) !== "on" && !post_password_required() ) {
					get_template_part('template/article-bottom-meta');
				}
			?>
		</div>
	</article>
</div>