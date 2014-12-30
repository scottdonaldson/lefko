<!DOCTYPE html>

<!--

    Design + Code by Parsley & Sprouts (www.parsleyandsprouts.com)

-->

<html class="no-js touch" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?php wp_title(''); ?></title>

    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" />

    <link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" />

    <link rel="stylesheet" href="<?php echo bloginfo('template_url'); ?>/style.css">
    <link rel="stylesheet" href="<?php echo bloginfo('template_url'); ?>/css/style.css">

    <script src="<?php echo bloginfo('template_url'); ?>/js/lib/modernizr-2.8.3.js"></script>

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

        <?php get_template_part('events'); ?>

        <a class="email alignleft" href="mailto:dlefkowi@carleton.edu"></a>
        <a class="ps alignright" href="http://www.parsleyandsprouts.com" target="_blank" title="Site created by Parsley &amp; Sprouts"></a>
        <p class="copyright">&copy; <?= date('Y'); ?> David Lefkowitz</p>

    </section>
</header>

<?php
// set a random background for the home page
$bg = rand(1, 6);
$class = is_front_page() ? 'background'.$bg : '';
?>
<div id="main" role="main" class="<?= $class; ?>">
