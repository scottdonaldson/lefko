<?php 
/*
Template Name: Bio
*/
get_header(); the_post(); ?>

<div class="header">
	<h1 id="entry-title"><?php the_title(); ?></h1>
</div>

<div id="content" class="bio clearfix">
	<div class="pos">
		<div class="spacer"></div>
	</div>

	<?php 
	// Only show right content if there is at least one featured image
	if (get_field('image_1')) { ?>
	<div class="right">
		<div class="featured bio">
			<img src="<?php the_field('image_1'); ?>" alt="<?php the_field('caption_1'); ?>" />
			<small><?php the_field('caption_1'); ?></small>
		</div>
	</div>
	<?php } ?>

	<?php 
	// Only show left div if there is at least one featured image
	if (get_field('image_1')) { ?>
	<div class="left">
	<?php } ?>
		<?php the_content(); ?>
	<?php 
	// And close the div
	if (get_field('image_1')) { ?>
	</div>
	<?php } ?>
</div>

<?php get_footer(); ?>