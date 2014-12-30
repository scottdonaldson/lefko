<?php
/*
Template Name: Events
*/
get_header();

$events = array();

$year = isset($_GET['archive']) ? intval($_GET['archive']) : idate('Y');
$date = date('Ymd');

function year_link($the_year) {
	global $year;

	$class = $the_year == $year ? 'archive active' : 'archive';
	$text = $the_year == date('Y') ? date('Y') . ' &amp; Upcoming' : $the_year;
	$spacer = $the_year == 2012 ? '' : ' | ';

	?>
	<a class="<?= $class; ?>" href="<?php the_permalink(); ?>?archive=<?= $the_year; ?>"><?= $text; ?></a>
	<?= $spacer; ?>
<?php }

function output_event($event) {

	$link = $event['link'];
	$title = $event['title'];
	$start = $event['start'];
	$end = $event['end'];
	$location = $event['location'];
	$description = $event['description'];

	?>
	<article>
		<h2>
			<?php if ( $link ) { ?>
			<a href="<?= $link; ?>" rel="bookmark" title="<?= $title; ?>"><?= $title; ?></a>
			<?php } else {
			echo $title;
			}
			?>
		</h2>
		<p>
			<?= dl_date($start); ?>
			<?php if ( $end ) {
				echo ' - ' . dl_date($end);
			}
			?>
		</p>
		<p>
			<?= $location; ?>
		</p>
		<?= $description; ?>
	</article>
	<?php
}

function year_match($start, $end) {

	global $year;

	// this gives us the year as an integer
	$start_year = floor($start / 10000);
	$end_year = floor($end / 10000);

	if ( $year == $start_year || $year == $end_year ) {
		return true;
	} elseif ( $year == idate('Y') && ( $year <= $start_year || $year <= $end_year ) ) {
		return true;
	}

	return false;
}

$event_query = new WP_Query(array(
	'meta_key' => 'start_date', // name of custom field
	'posts_per_page' => -1,
	'orderby' => 'meta_value_num',
	'order' => 'ASC',
	'post_type' => 'dl_events',
));

while ( $event_query->have_posts() ) : $event_query->the_post();

	$start = intval(get_field('start_date'));
	$end = intval(get_field('end_date'));

	// 1. if there is no end date (single day event) and the event has passed, or
	// 2. if there is an end date (range) and the end date has passed, AND
	// 3. the year matches,
	// then add to events array
	if ( ( ( !$end && $date >= $start ) || ( $end && $date > $start ) ) && year_match($start, $end) ) {

		$events[] = array(
			'link' => get_field('link'),
			'title' => get_the_title(),
			'start' => $start,
			'end' => $end,
			'location' => get_field('location')
		);
	}

endwhile;
wp_reset_query();

?>

<div class="header">
	<h1 id="entry-title"><?php the_title(); ?></h1>
</div>

<div id="content" class="clearfix">
	<div class="pos">
		<div class="spacer"></div>
	</div>

	<div class="left">
		<h3>
			<?php for ( $i = intval(date('Y')); $i >= 2012; $i-- ) {
				year_link($i);
			} ?>
		</h3>
		<?php
		foreach ( $events as $key => $event ) {
			output_event($event);
		}
		?>
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
