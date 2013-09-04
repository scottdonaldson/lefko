<!DOCTYPE html>

<!--

    Design + Code by Parsley & Sprouts (www.parsleyandsprouts.com)

-->

<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?php wp_title(''); ?></title>

    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" />

    <link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" />

    <link rel="stylesheet" href="<?php echo bloginfo('template_url'); ?>/style.css">

    <link rel="stylesheet" href="<?php echo bloginfo('template_url'); ?>/style.css">
    <link rel="stylesheet" href="<?php echo bloginfo('template_url'); ?>/css/style.css">

    <script src="<?php echo bloginfo('template_url'); ?>/js/libs/modernizr-2.5.3.min.js"></script>

<?php wp_head(); ?>  
</head>

<body <?php body_class(); ?>>
<!--[if lt IE 8]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

<header id="header">
	<?php if (is_home()) { ?>
	<h1 id="site-title">
        <a href="<?php echo home_url(); ?>" rel="home" title="David Lefkowitz">David Lefkowitz</a>
    </h1>
	<?php } else { ?>
	<h3 id="site-title">
        <a href="<?php echo home_url(); ?>" rel="home" title="David Lefkowitz">David Lefkowitz</a>
    </h3>
	
    <?php } 
    $walker = new Thumb_Walker;
    $args = array(
        'theme_location' => 'primary-menu',
        'walker' => $walker,
    ); ?>
    <div class="standard">
    	<?php wp_nav_menu($args); ?>
    </div>
    <div class="mobile">
        <?php 
        $m_walker = new Mobile_Walker;
        $mobile = array(
            'theme_location' => 'primary-menu',
            'walker' => $m_walker,
        ); wp_nav_menu($mobile); ?>
    </div>

    <section class="below">
        <h2>Current &amp; Upcoming Events</h2>
        <div class="events clearfix">
            <?php 
            $date = date('Ymd');
            query_posts(array(
                'meta_key' => 'start_date', // name of custom field
                'numberposts' => -1,
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'post_type' => 'dl_events',
            ));
            while (have_posts()) : the_post();

                if (get_field('link')) {
                    $link = get_field('link');
                }
                // Is this a single date event?
                if (get_field('end_date') == '') { 
                    // Make sure it hasn't passed
                    if ($date <= get_field('start_date')) { ?>
                        <article <?php post_class(); ?>>
                            <?php if (get_field('link')) { ?>
                            <a href="<?php echo get_field('link'); ?>" rel="bookmark" title="<?php the_title(); ?>">
                                <?php the_title(); ?>
                            </a>
                            <?php } else { the_title(); } ?>
                            <p>
                                <?php 
                                $start = get_field('start_date');
                                echo dl_date($start); ?>
                                <br />
                                <?php the_field('location'); ?>
                            </p>
                        </article><?php 
                    }
                // Is there an end date? (range of dates) 
                } elseif (get_field('end_date') != '') { 
                    // Make sure it hasn't passed
                    if ($date <= get_field('end_date')) { ?>
                        <article <?php post_class(); ?>>
                            <?php if (get_field('link')) { ?>
                            <a href="<?php echo get_field('link'); ?>" target="_blank" title="<?php the_title(); ?>">
                                <?php the_title(); ?>
                            </a>
                            <?php } else { the_title(); } ?>
                            <p>
                                <?php 
                                $start = get_field('start_date');
                                $end = get_field('end_date');
                                echo dl_date($start).' - '.dl_date($end); ?>
                                <br />
                                <?php the_field('location'); ?>
                            </p>
                        </article><?php 
                    }
                } 
            endwhile;
            wp_reset_query(); ?>
        </div><!-- .events -->
        <div class="up faded"></div>
        <div class="down"></div>

        <div class="archive">
            <a href="<?php echo home_url(); ?>/events-exhibitions/?archive=<?php echo date('Y'); ?>">Archived Events</a>
        </div>

        <a class="email alignleft" href="mailto:dlefkowi@carleton.edu"></a>
        <a class="ps alignright" href="http://www.parsleyandsprouts.com" target="_blank" title="Site created by Parsley &amp; Sprouts"></a>
        <p class="copyright">&copy; <?php echo date('Y'); ?> David Lefkowitz</p>
       
    </section>
</header>

<?php 
// set a random background for the home page
$bg = rand(1, 7); 
$class = is_front_page() ? 'background'.$bg : '';
?>
<div id="main" role="main" class="<?= $class; ?>">