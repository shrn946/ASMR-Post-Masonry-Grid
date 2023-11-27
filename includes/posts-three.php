<?php
// Post-Loop.php

function masonry_post_grid($atts) {
    ob_start();

    // Shortcode attributes
    $atts = shortcode_atts(
        array(
            'exclude_category' => '', // Comma-separated list of category slugs to exclude
        ),
        $atts,
        'masonry_post_grid'
    );

    // Exclude category parameter
    $exclude_category = explode(',', $atts['exclude_category']);

    // Output filter list
    ?>
    <ul class="filter">
        <li><a href="#all" data-filter="*" class="active">All</a></li>
        <?php
        $categories = get_categories();

        foreach ($categories as $category) :
            if (!in_array($category->slug, $exclude_category)) {
                $category_slug = esc_attr(sanitize_title($category->name));
        ?>
                <li><a href="#<?php echo $category_slug; ?>" data-filter=".<?php echo $category_slug; ?>"><?php echo esc_html($category->name); ?></a></li>
        <?php
            }
        endforeach;
        ?>
    </ul>

    <div class="grid">
        <?php
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'order'          => 'DESC',
            'orderby'        => 'date',
            'category__not_in' => array_map('get_cat_ID', $exclude_category), // Exclude specified categories
        );

        $query = new WP_Query($args);

        // Get the fallback image URL from the plugin directory
        $fallback_image_url = plugins_url('../img/fallback-img.jpg', __FILE__);

        // Counter variable to track post index
        $post_index = 0;

        while ($query->have_posts()) : $query->the_post();
            $categories = get_the_category(); // Get post categories

            // Get the featured image URL
            $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');

            // Fallback to default image if no featured image is set
            $background_image = $featured_image_url ? $featured_image_url : $fallback_image_url;

            // Increment the post index
            $post_index++;

            // Determine the CSS classes for the grid items
            $grid_item_classes = 'grid-item loading'; // Added 'loading' class
            if ($post_index === 2) {
                $grid_item_classes .= ' grid-item--width2';
            } elseif ($post_index === 3) {
                $grid_item_classes .= ' grid-item--width3';
            }
        ?>

            <!-- Card <?php echo esc_attr($post_index); ?> -->
            <div class="card <?php echo esc_attr($grid_item_classes); ?> <?php foreach ($categories as $category) {
                                                                            if (!in_array($category->slug, $exclude_category)) {
                                                                                echo esc_attr($category->slug) . ' ';
                                                                            }
                                                                        } ?>">
                <div class="card__image">
                    <img src="<?php echo esc_url($background_image); ?>" alt="">
                    <div class="card__overlay card__overlay--blue">
                        <div class="card__overlay-content">
                            <ul class="card__meta">
                                <li><i class="fa fa-tag"></i> <?php echo esc_html($categories[0]->name); ?></li>
                                <li><i class="fa fa-clock-o"></i> <?php echo esc_html(get_the_date()); ?></li>

                            </ul>
                            <a class="card__title" style="text-decoration:none; " href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a>

                            <ul class="card__meta card__meta--last">
                                <li><i class="fa fa-user"></i> <?php echo esc_html(get_the_author()); ?></li>
                                <li><i class="fa fa-facebook-square"></i> <a style="text-decoration:none;" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(get_permalink()); ?>" target="_blank">Share</a></li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile;

        wp_reset_postdata();
        ?>
    </div>

    <!-- Add the following script to your HTML file -->
    <!-- Add this script to your HTML file -->
    <script>
        // jQuery script to delay the removal of 'loading' class and add a slide effect
        jQuery(document).ready(function ($) {
            // Delay the removal of 'loading' class by 500 milliseconds (adjust as needed)
            setTimeout(function () {
                $('.grid-item').removeClass('loading').addClass('loaded');
            }, 500);
        });
    </script>

    <!-- Add this style to your CSS file -->
    <style>
        /* Delayed fade in effect */
        .grid-item {
            opacity: 0;
            transition: opacity 1000ms ease-in-out;
        }

        /* Add different delay for each grid item */
        .grid-item:nth-child(1) {
            transition-delay: 0.2s;
        }

        .grid-item:nth-child(2) {
            transition-delay: 0.4s;
        }

        .grid-item:nth-child(3) {
            transition-delay: 0.6s;
        }

        .grid-item.loaded {
            opacity: 1;
        }
    </style>
    
    <?php
    return ob_get_clean();
}

// Create a new shortcode to use the function in posts or pages
add_shortcode('masonry_post_grid', 'masonry_post_grid');

// Shortcode to show all posts without any category filters
function show_all_posts_shortcode() {
    ob_start();
    echo do_shortcode('[masonry_post_grid exclude_category=""]');
    return ob_get_clean();
}

// Create a new shortcode for showing all posts
add_shortcode('show_all_posts', 'show_all_posts_shortcode');    ?>