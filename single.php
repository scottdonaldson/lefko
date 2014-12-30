<?php get_header(); the_post(); ?>

<div class="header">
	<h1 id="entry-title"><?php the_title(); ?></h1>
</div>

<div id="content" class="clearfix">
	<div class="pos">
		<div class="spacer"></div>
	</div>

	<?php
	if (get_field('gallery')) { ?>
		<div id="gallery">
			<ul>
				<?php
				$i = 0;
				while (has_sub_field('gallery')) {
					$i++; ?>
					<li class="<?php if ($i == 1) { echo ' initial shown'; } ?>">
						<figure>
							<img src="<?php the_sub_field('image'); ?>" alt="<?php the_sub_field('title'); ?>, David Lefkowitz, <?php the_sub_field('year'); ?>" />
							<figcaption class="clearfix description">
								<p class="left
									<?php if (get_sub_field('materials') && get_sub_field('dimensions')) {
										echo 'third';
									} elseif (get_sub_field('materials') && !get_sub_field('dimensions')) {
										echo 'two-thirds';
									} elseif (!get_sub_field('materials') && get_sub_field('dimensions')) {
										echo 'two-thirds';
									} ?>"><?php the_sub_field('title');
									if (get_sub_field('year')) { echo ', '.get_sub_field('year'); } ?></p>
								<?php
								if (get_sub_field('materials') && !get_sub_field('dimensions')) {
									echo '<p class="right third">'.get_sub_field('materials').'</p>';
								} elseif (!get_sub_field('materials') && get_sub_field('dimensions')) {
									echo '<p class="right third">'.get_sub_field('dimensions');
								} elseif (get_sub_field('materials') && get_sub_field('dimensions')) {
									echo '<p class="middle third">'.get_sub_field('materials').'</p>';
									echo '<p class="right third">'.get_sub_field('dimensions').'</p>';
								} ?>
							</figcaption>
						</figure>
					</li>
				<?php } ?>
			</ul>
		</div><!-- #gallery -->
		<div id="thumbs">
			<div class="prev"></div>
			<div class="container">
			 	<ul>
			    <?php
		    	$i = 0;
		    	while (has_sub_field('gallery')) {
		    		$i++; ?>
					<li <?php
						if (get_sub_field('detail') == true) { echo 'class="detail"'; } else { echo 'class="orig"'; } ?>>
						<img src="<?php the_sub_field('thumb'); ?>" alt="<?php the_sub_field('title'); ?>" />
						<div class="cover"></div>
					</li>
				<?php } ?>
				</ul>
			</div><!-- .container -->
		  	<div class="next"></div>
		</div><!-- #thumbs -->
	<?php } ?>

</div>

<?php get_footer(); ?>
