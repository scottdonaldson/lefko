<?php 
/*
Template Name: Review/Article
*/
get_header(); the_post(); ?>

<div class="header">
	<h1 id="entry-title"><?php the_title(); ?></h1>
</div>

<div id="content" class="review clearfix">
	<div class="pos">
		<div class="spacer"></div>
	</div>
	
	<?php the_content(); ?>

</div>

<?php get_footer(); ?>