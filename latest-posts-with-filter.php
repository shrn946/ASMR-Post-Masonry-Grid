<?php
/*
Plugin Name: ASMR Masonry Grid Wordpress
Description: A simple WordPress plugin to display the latest posts with category filtering.
Version: 2.0
Author: Hassan Naqvi
WP Design Lab
*/

// Include the file
include(plugin_dir_path(__FILE__) . 'includes/posts-three.php');
include(plugin_dir_path(__FILE__) . 'includes/full-width.php');


// Enqueue scripts and styles
function latest_posts_with_filter_scripts() {
    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue Isotope
    wp_enqueue_script('isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.js', array('jquery'), '3.0.6', true);
	
    // Enqueue your custom script
    wp_enqueue_script('latest-posts-with-filter', plugin_dir_url(__FILE__) . 'js/latest-posts-with-filter.js', array('isotope'), '1.0', true);

    // Enqueue styles
    wp_enqueue_style('latest-posts-with-filter-styles', plugin_dir_url(__FILE__) . 'css/latest-posts-with-filter.css');
}
add_action('wp_enqueue_scripts', 'latest_posts_with_filter_scripts');


// Function to add Open Graph meta tags
function add_facebook_open_graph_tags() {
    if (is_single()) {
        global $post;

        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />';
        echo '<meta property="og:type" content="article" />';
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />';
        echo '<meta property="og:image" content="' . esc_url(get_the_post_thumbnail_url($post->ID, 'full')) . '" />';
        echo '<meta property="og:description" content="' . esc_attr(wp_trim_words(get_the_excerpt(), 30)) . '" />';
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />';
    }
}

// Hook the function into the head
add_action('wp_head', 'add_facebook_open_graph_tags');



function masonry_grid_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=masonry-grid-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'masonry_grid_settings_link');

// Settings page content
function masonry_grid_settings_page() {
    ?>
    <div class="wrap" style="font-size: 20px;">
        <h1>ASMR Masonry Grid Shortcode</h1>
        <p>Welcome to the settings page for the Masonry Grid plugin.</p>
        
        <h2>How to Use Shortcode</h2>
        
        <p style="font-size: 20px; font-weight: bold;">To display the Masonry Grid on your site, use the following shortcodes:</p>
        
        <pre>Use this shortcode to display all posts: [masonry_post_grid]</pre>
        <pre>Use this shortcode for a full-width layout: [full_width_post_grid]</pre>
        
        <pre>Customize the shortcode with the 'exclude_category' attribute: [masonry_post_grid exclude_category="category-slug, category-slug"]</pre>
        
        <pre>Customize the full-width shortcode with the 'exclude_category' attribute: [full_width_post_grid exclude_category="offers"]</pre>
        
        <div class="video-link">
            <h3>Video Tutorial</h3>
            
            <iframe width="560" height="315" src="https://www.youtube.com/embed/1ZmUUkivfwg?si=fVdtF579rpRE-nY9" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
    </div>
    <?php
}

// Add menu item
function masonry_grid_menu() {
    add_options_page(
        'Masonry Grid Settings',
        'Masonry Grid',
        'manage_options',
        'masonry-grid-settings',
        'masonry_grid_settings_page'
    );
}

add_action('admin_menu', 'masonry_grid_menu');
?>
