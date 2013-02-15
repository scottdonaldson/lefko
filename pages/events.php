<?php 
/*
Template Name: Events
*/ 
get_header(); 
if (isset($_GET['archive'])) {
	$year = $_GET['archive'];
} ?>

<div class="header">
	<h1 id="entry-title"><?php the_title(); ?></h1>
</div>

<div id="content" class="clearfix">
	<div class="pos">
		<div class="spacer"></div>
	</div>

	<div class="left">
		<h3>
			<a class="archive <?php if (!isset($_GET['archive'])) { echo 'active'; } ?>" href="<?php the_permalink(); ?>">Current &amp; Upcoming</a> | <a class="archive <?php if (isset($year) && $year == '2012') { echo 'active'; } ?>"href="<?php the_permalink(); ?>/?archive=2012">Archived 2012</a>
		</h3>
		<?php 
		$date = date('Ymd');
		query_posts(array(
			'meta_key' => 'start_date',
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
			'post_type' => 'dl_events',
			'posts_per_page' => -1,
			)
		);
		while (have_posts()) : the_post();

			// If this is not an archive, then we just want upcoming events
			if (!isset($_GET['archive'])) {

				// Is this a single date event?
				if (get_field('end_date') == '') { 
					// Make sure it hasn't passed
					if ($date <= get_field('start_date')) { ?>
						<article <?php post_class(); ?>>
							<h2>
								<?php if (get_field('link')) { ?>
								<a href="<?php echo get_field('link'); ?>" target="_blank" title="<?php the_title(); ?>">
									<?php the_title(); ?>
								</a>
								<?php } else { the_title(); } ?>
							</h2>
							<p><?php 
								$start = get_field('start_date');
								echo dl_date($start); ?>
							</p>
							<p><?php the_field('location'); ?></p>
							<?php the_field('description'); ?>
						</article><?php 
					}
				// Is there an end date? (range of dates) 
				} elseif (get_field('end_date') != '') { 
					// Make sure it hasn't passed
					if ($date <= get_field('end_date')) { ?>
						<article <?php post_class(); ?>>
							<h2>
								<?php if (get_field('link')) { ?>
								<a href="<?php echo get_field('link'); ?>" rel="bookmark" title="<?php the_title(); ?>">
									<?php the_title(); ?>
								</a>
								<?php } else { the_title(); } ?>
							</h2>
							<p><?php 
								$start = get_field('start_date');
								$end = get_field('end_date');
								echo dl_date($start).' - '.dl_date($end); ?>
							</p>
							<p><?php the_field('location'); ?></p>
							<?php the_field('description'); ?>
						</article><?php 
					}
				} 

			// Otherwise, this is an archive and we only want events that have passed
			} else { 
				// Set year
				$year = $_GET['archive']; 

				// Make sure either start or end date is in the year
				// that we're looking at 
				if (
					$year == substr(get_field('start_date'), 0, 4) || 
					$year == substr(get_field('end_date'), 0, 4)
					) {
					// Is this a single date event?
					if (get_field('end_date') == '') { 
						// Make sure it has passed
						if ($date > get_field('start_date')) { ?>
							<article <?php post_class(); ?>>
								<h2>
									<?php if (get_field('link')) { ?>
									<a href="<?php echo get_field('link'); ?>" rel="bookmark" title="<?php the_title(); ?>">
										<?php the_title(); ?>
									</a>
									<?php } else { the_title(); } ?>
								</h2>
								<p><?php 
									$start = get_field('start_date');
									echo dl_date($start); ?>
								</p>
								<p><?php the_field('location'); ?></p>
								<?php the_field('description'); ?>
							</article><?php 
						}
					// Is there an end date? (range of dates) 
					} elseif (get_field('end_date') != '') { 
						// Make sure it has passed
						if ($date > get_field('end_date')) { ?>
							<article <?php post_class(); ?>>
								<h2>
									<?php if (get_field('link')) { ?>
									<a href="<?php echo get_field('link'); ?>" rel="bookmark" title="<?php the_title(); ?>">
										<?php the_title(); ?>
									</a>
									<?php } else { the_title(); } ?>
								</h2>
								<p><?php 
									$start = get_field('start_date');
									$end = get_field('end_date');
									echo dl_date($start).' - '.dl_date($end); ?>
								</p>
								<p><?php the_field('location'); ?></p>
								<?php the_field('description'); ?>
							</article><?php 
						}
					}
				}
			}
		endwhile; wp_reset_query(); ?>
	</div>

	<?php 
	// Only show right content if there is at least one featured image
	if (get_field('image_1')) { ?>
	<div class="right">

		<img src="<?php the_field('image_1'); ?>" alt="<?php the_field('caption_1'); ?>" />
		<small><?php the_field('caption_1'); ?></small>

		<img src="<?php the_field('image_2'); ?>" alt="<?php the_field('caption_2'); ?>" />
		<small><?php the_field('caption_2'); ?></small>	

	</div>
	<?php } ?>

</div><!-- #content -->

<?php get_footer(); ?>