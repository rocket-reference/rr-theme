<?php
// Force site URL
update_option('siteurl', 'https://rocketreference.com');
update_option('home', 'https://rocketreference.com');

// Add the Parent Styles from this Folder for the Newspaper Theme
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 1001);
function theme_enqueue_styles() {
    wp_enqueue_style('td-theme', get_template_directory_uri() . '/style.css', '', TD_THEME_VERSION, 'all' );
    wp_enqueue_style('td-theme-child', get_stylesheet_directory_uri() . '/style.css', array('td-theme'), TD_THEME_VERSION . 'c', 'all' );
}

// Add the custom post types to the Category pages
function add_custom_post_types_cat($query) {
    if (is_category() || is_tag() && empty ($query->query_vars['suppress_filters'])) {
        $query->set ('post_type', array (
            'post', 'nav_menu_item', 'engine'
        ));
    return $query;
    }
}
add_filter ('pre_get_posts', 'add_custom_post_types_cat');

// Sort the categories alphabetically
function category_alpha ($query) {
    if ($query->is_tax('category') && $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
    }
}
add_action ('pre_get_posts', 'category_alpha');

// Automatically adds ID's to headings to we can link to them later
function create_heading_ids( $content ) {
	$content = preg_replace_callback( '/(\<h[1-6](.*?))\>(.*)(<\/h[1-6]>)/i', function( $matches ) {
		if ( ! stripos( $matches[0], 'id=' ) ) :
			$matches[0] = $matches[1] . $matches[2] . ' id="' . sanitize_title( $matches[3] ) . '">' . $matches[3] . $matches[4];
		endif;
		return $matches[0];
	}, $content );
    return $content;
}
add_filter( 'the_content', 'create_heading_ids' );