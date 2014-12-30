<?php

$cur_upcoming = array();

function dl_the_event($event) { ?>
    <article>
        <?php if ( $event['link'] ) { ?>
            <a href="<?= $event['link']; ?>" rel="bookmark" title="<?= $event['title']; ?>">
                <?= $event['title']; ?>
            </a>
        <?php } else {
            echo $event['title'];
        } ?>
        <p>
            <?= dl_date($event['start']); ?>
            <?php if ( $event['end'] ) {
                echo ' - ' . dl_date($event['end']);
            }
            ?>
            <br>
            <?= $event['location']; ?>
        </p>
    </article>
<?php }

$date = date('Ymd');
$event_query = new WP_Query(array(
    'meta_key' => 'start_date', // name of custom field
    'posts_per_page' => -1,
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'post_type' => 'dl_events',
));

while ( $event_query->have_posts() ) : $event_query->the_post();

// If a single day event and has not yet passed,
// or a multiple day event and end date has not yet passed, add to current/upcoming array
if ( ( !get_field('end_date') && $date <= intval( get_field('start_date') ) ) || ( get_field('end_date') && $date <= intval( get_field('end_date') ) ) ) {

    $cur_upcoming[] = array(
        'link' => get_field('link'),
        'title' => get_the_title(),
        'start' => get_field('start_date'),
        'end' => get_field('end_date'),
        'location' => get_field('location')
    );
}

endwhile;
wp_reset_query();

if ( count($cur_upcoming) > 0 ) {
?>

    <h2>Current &amp; Upcoming Events</h2>
    <div class="events clearfix">
        <?php foreach ($cur_upcoming as $key => $event) {
            dl_the_event($event);
        } ?>
    </div><!-- .events -->
    <div class="up faded"></div>
    <div class="down"></div>

<?php } ?>

<div class="archive">
    <a href="<?php echo home_url(); ?>/events-exhibitions/?archive=<?= date('Y'); ?>">Archived Events</a>
</div>
