<?php

// Register navigation menu
add_action( 'init', 'register_my_menu' );
function register_my_menu() {
	register_nav_menu( 'primary-menu', 'Primary Menu' );
}

class Thumb_Walker extends Walker_Nav_Menu {
	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes = empty ( $item->classes ) ? array () : (array) $item->classes;

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );

		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . ' class="' . $class_names .' level-'.$depth.'">';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		if ( $item->description ) {
		  $item_output .= '<span class="hidden menu-description">' . $item->description . '</span>';
		}
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
class Mobile_Walker extends Walker_Nav_Menu {
	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes = empty ( $item->classes ) ? array () : (array) $item->classes;

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );

		$output .= $indent . '<li id="menu-item-'. $item->ID . '-1"' . ' class="' . $class_names .' level-'.$depth.'">';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

// Remove some stuff from head
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'wp_generator');

// Remove a few admin pages
add_action( 'admin_menu', 'my_remove_menus', 999 );
function my_remove_menus() {
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'upload.php' );
	remove_menu_page( 'link-manager.php' );
	remove_submenu_page( 'edit.php', 'edit-tags.php' );
}

// Add admin scripts
add_action('admin_head', 'dl_admin_css');
function dl_admin_css() {
	$template_url = get_bloginfo('template_url');
	echo '<link rel="stylesheet" href="'.$template_url.'/css/admin-style.css" />';
}
add_action('admin_footer', 'dl_admin_js');
function dl_admin_js() {
	$template_url = get_bloginfo('template_url');
	echo '<script src="'.$template_url.'/js/admin.js"></script>';
}

// Add custom post type for events
class dl_events {
	function dl_events() {
		add_action('init',array($this,'create_post_type'));
	}
	function create_post_type() {
		$labels = array(
		    'name' => 'Events',
		    'singular_name' => 'Event',
		    'add_new' => 'Add Event',
		    'all_items' => 'All Events',
		    'add_new_item' => 'Add Event',
		    'edit_item' => 'Edit Event',
		    'new_item' => 'New Event',
		    'view_item' => 'View Event',
		    'search_items' => 'Search Events',
		    'not_found' =>  'No events found',
		    'not_found_in_trash' => 'No events found in trash',
		    'parent_item_colon' => '',
		    'menu_name' => 'Events'
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_nav_menus' => false, 
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'menu_position' => 5,
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array('title','editor'),
			'has_archive' => true,
			'rewrite' => array( 'slug' => 'events', 'with_front' => true ),
			'query_var' => true,
			'can_export' => true
		); 
		register_post_type('dl_events',$args);
	}
}
$dl_events = new dl_events();

// Convert string into date format
function dl_date($string) {
	$d = substr($string, -2);
	$m = substr($string, -4, -2);
	$y = substr($string, 2, 2);

	return $m.'.'.$d.'.'.$y;
}				

// Remove 'Right Now' dashboard widget
function dl_dashboard_widgets() {
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	wp_add_dashboard_widget ( 'dl_dashboard_right_now', 'Right Now', 'dl_dashboard_right_now' );
} 
add_action('wp_dashboard_setup', 'dl_dashboard_widgets' );

function dl_dashboard_right_now() {
	global $wp_registered_sidebars;

	$num_series = wp_count_posts( 'post' );
	$num_pages = wp_count_posts( 'page' );
	$num_events  = wp_count_posts( 'dl_events' );
	$num_cats  = wp_count_terms('category');

	$num_comm = wp_count_comments( );

	echo '<div class="clearfix">';
	echo "\n\t".'<div class="table table_content">';
	echo "\n\t".'<p class="sub">' . __('Content') . '</p>'."\n\t".'<table>';
	echo "\n\t".'<tr class="first">';

	// Series
	$num = number_format_i18n( $num_series->publish );
	$text = _n( 'Series', 'Series', intval($num_series->publish) );
	if ( current_user_can( 'edit_posts' ) ) {
		$num = "<a href='edit.php'>$num</a>";
		$text = "<a href='edit.php'>$text</a>";
	}
	echo '<td class="first b b-posts">' . $num . '</td>';
	echo '<td class="t posts">' . $text . '</td>';

	echo '</tr><tr>';

	// Pages
	$num = number_format_i18n( $num_pages->publish );
	$text = _n( 'Page', 'Pages', $num_pages->publish );
	if ( current_user_can( 'edit_pages' ) ) {
		$num = "<a href='edit.php?post_type=page'>$num</a>";
		$text = "<a href='edit.php?post_type=page'>$text</a>";
	}
	echo '<td class="first b b_pages">' . $num . '</td>';
	echo '<td class="t pages">' . $text . '</td>';

	echo '</tr><tr>';

	// Pages
	$num = number_format_i18n( $num_events->publish );
	$text = _n( 'Event', 'Events', $num_events->publish );
	if ( current_user_can( 'edit_posts' ) ) {
		$num = "<a href='edit.php?post_type=dl_events'>$num</a>";
		$text = "<a href='edit.php?post_type=dl_events'>$text</a>";
	}
	echo '<td class="first b b_pages">' . $num . '</td>';
	echo '<td class="t events">' . $text . '</td>';

	echo '</tr><tr>';

	// Categories
	$num = number_format_i18n( $num_cats );
	$text = _n( 'Category', 'Categories', $num_cats );
	if ( current_user_can( 'manage_categories' ) ) {
		$num = "<a href='edit-tags.php?taxonomy=category'>$num</a>";
		$text = "<a href='edit-tags.php?taxonomy=category'>$text</a>";
	}
	echo '<td class="first b b-cats">' . $num . '</td>';
	echo '<td class="t cats">' . $text . '</td>';

	echo '</tr>';
	do_action('right_now_content_table_end');
	echo "\n\t</table>\n\t</div>";

	echo "\n\t".'<div class="system table table_content">';
	echo "\n\t".'<p class="sub">' . __('System') . '</p>';
	$theme = wp_get_theme();

	echo "\n\t<p>";

	if ( $theme->errors() ) {
		if ( ! is_multisite() || is_super_admin() )
			echo '<span class="error-message">' . __('ERROR: The themes directory is either empty or doesn&#8217;t exist. Please check your installation.') . '</span>';
	} elseif ( ! empty($wp_registered_sidebars) ) {
		$sidebars_widgets = wp_get_sidebars_widgets();
		$num_widgets = 0;
		foreach ( (array) $sidebars_widgets as $k => $v ) {
			if ( 'wp_inactive_widgets' == $k || 'orphaned_widgets' == substr( $k, 0, 16 ) )
				continue;
			if ( is_array($v) )
				$num_widgets = $num_widgets + count($v);
		}
		$num = number_format_i18n( $num_widgets );

		$switch_themes = $theme->display('Name');
		if ( current_user_can( 'switch_themes') )
			$switch_themes = '<a href="themes.php">' . $switch_themes . '</a>';
		if ( current_user_can( 'edit_theme_options' ) ) {
			printf(_n('Theme: <span class="b">%1$s</span> with <span class="b"><a href="widgets.php">%2$s Widget</a></span>', 'Theme: <span class="b">%1$s</span> with <span class="b"><a href="widgets.php">%2$s Widgets</a></span>', $num_widgets), $switch_themes, $num);
		} else {
			printf(_n('Theme: <span class="b">%1$s</span> with <span class="b">%2$s Widget</span>', 'Theme: <span class="b">%1$s</span> with <span class="b">%2$s Widgets</span>', $num_widgets), $switch_themes, $num);
		}
	} else {
		if ( current_user_can( 'switch_themes' ) )
			printf( __('Theme: <span class="b"><a href="themes.php">%1$s</a></span>'), $theme->display('Name') );
		else
			printf( __('Theme: <span class="b">%1$s</span>'), $theme->display('Name') );
	}
	echo '</p>';

	// Check if search engines are blocked.
	if ( !is_network_admin() && !is_user_admin() && current_user_can('manage_options') && '1' != get_option('blog_public') ) {
		$title = apply_filters('privacy_on_link_title', __('Your site is asking search engines not to index its content') );
		$content = apply_filters('privacy_on_link_text', __('Search Engines Blocked') );

		echo "<p><a href='options-privacy.php' title='$title'>$content</a></p>";
	}

	update_right_now_message();

	echo "\n\t".'<br class="clear" /></div></div><!-- .clearfix -->';
	do_action( 'rightnow_end' );
	do_action( 'activity_box_end' );
}

// Editor can edit menu

function give_user_edit() {
	if(current_user_can('edit_others_posts')) {
		global $wp_roles;
		$wp_roles->add_cap('editor','edit_theme_options' );
	}
}
add_action('admin_init', 'give_user_edit', 10, 0);

?>