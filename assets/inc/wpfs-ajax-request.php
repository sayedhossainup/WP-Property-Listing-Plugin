<?php

/*
 *
 * 	***** WP Property for sale *****
 *
 * 	Ajax Request
 * 	
 */
// If this file is called directly, abort. //
if (!defined('WPINC')) {
    die;
} // end if
/*
  Ajax Requests
 */

function enqueue_properties_search_scripts() {
    wp_enqueue_script('properties-search', plugin_dir_url(__FILE__) . '../js/properties-search.js', ['jquery'], null, true);

    wp_localize_script('properties-search', 'properties_ajax', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
}

add_action('wp_enqueue_scripts', 'enqueue_properties_search_scripts');

function properties_custom_admin_styles() {
    wp_enqueue_style('properties-custom-admin-styles', plugin_dir_url(__FILE__) . '../css/property-details.css', [], '1.0.0');
}

add_action('admin_enqueue_scripts', 'properties_custom_admin_styles');


function load_google_maps_api() {
    wp_enqueue_script(
        'google-maps',
        'https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY',
        null,
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'load_google_maps_api');


/* * ********************** */

function ajax_properties_search() {
    $args = [
        'post_type' => 'property',
        'posts_per_page' => -1,
        'meta_query' => []
    ];

    // Apply filters if present
    if (!empty($_POST['location'])) {
        $args['meta_query'][] = [
            'key' => 'property_location',
            'value' => sanitize_text_field($_POST['location']),
            'compare' => 'LIKE'
        ];
    }
    
    // Apply filters if present
    if (!empty($_POST['location_area'])) {
        $args['meta_query'][] = [
            'key' => 'property_location_area',
            'value' => sanitize_text_field($_POST['location_area']),
            'compare' => 'LIKE'
        ];
    }

    // Replace property_type meta query with a tax_query
    if (!empty($_POST['property_type'])) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'property_type',
                'field' => 'slug', // Use slug for comparison
                'terms' => sanitize_text_field($_POST['property_type']),
            ],
        ];
    }

    if (!empty($_POST['min_beds'])) {
        $args['meta_query'][] = [
            'key' => 'property_beds',
            'value' => (int) $_POST['min_beds'],
            'type' => 'NUMERIC',
            'compare' => '>='
        ];
    }

    if (!empty($_POST['min_baths'])) {
        $args['meta_query'][] = [
            'key' => 'property_baths',
            'value' => (int) $_POST['min_baths'],
            'type' => 'NUMERIC',
            'compare' => '>='
        ];
    }

    if (!empty($_POST['min_price']) || !empty($_POST['max_price'])) {
        $price_filter = ['key' => 'property_price', 'type' => 'NUMERIC'];
        if (!empty($_POST['min_price'])) {
            $price_filter['value'][] = (int) $_POST['min_price'];
            $price_filter['compare'] = '>=';
        }
        if (!empty($_POST['max_price'])) {
            $price_filter['value'][] = (int) $_POST['max_price'];
            $price_filter['compare'] = '<=';
        }
        $args['meta_query'][] = $price_filter;
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) : $query->the_post();
            $bead = carbon_get_the_post_meta('property_beds');
            $bath = carbon_get_the_post_meta('property_baths');
            $price = carbon_get_the_post_meta('property_price');

            echo '<div class="property-item">';
            echo '<div class="property_thumbnail">' . get_the_post_thumbnail($post_id, 'thumbnail') . '</div>';
            echo '<div class="double-item"><span><a target="_blank" class="property_title" href="' . get_permalink() . '">' . get_the_title() . '</a></span>';
            echo '<span class="property_asset">';
            if (!empty($bead)):
                echo '<span><span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M32 32c17.7 0 32 14.3 32 32l0 256 224 0 0-160c0-17.7 14.3-32 32-32l224 0c53 0 96 43 96 96l0 224c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-32-224 0-32 0L64 416l0 32c0 17.7-14.3 32-32 32s-32-14.3-32-32L0 64C0 46.3 14.3 32 32 32zm144 96a80 80 0 1 1 0 160 80 80 0 1 1 0-160z"/></svg></span>' . carbon_get_the_post_meta('property_beds') . '</span>';
            endif;
            if (!empty($bath)):
                echo '<span><span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M96 77.3c0-7.3 5.9-13.3 13.3-13.3c3.5 0 6.9 1.4 9.4 3.9l14.9 14.9C130 91.8 128 101.7 128 112c0 19.9 7.2 38 19.2 52c-5.3 9.2-4 21.1 3.8 29c9.4 9.4 24.6 9.4 33.9 0L289 89c9.4-9.4 9.4-24.6 0-33.9c-7.9-7.9-19.8-9.1-29-3.8C246 39.2 227.9 32 208 32c-10.3 0-20.2 2-29.2 5.5L163.9 22.6C149.4 8.1 129.7 0 109.3 0C66.6 0 32 34.6 32 77.3L32 256c-17.7 0-32 14.3-32 32s14.3 32 32 32l448 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 256 96 77.3zM32 352l0 16c0 28.4 12.4 54 32 71.6L64 480c0 17.7 14.3 32 32 32s32-14.3 32-32l0-16 256 0 0 16c0 17.7 14.3 32 32 32s32-14.3 32-32l0-40.4c19.6-17.6 32-43.1 32-71.6l0-16L32 352z"/></svg></span>' . carbon_get_the_post_meta('property_baths') . '</span>';
            endif;
            if (!empty($price)):
                echo '<span><span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M312 24l0 10.5c6.4 1.2 12.6 2.7 18.2 4.2c12.8 3.4 20.4 16.6 17 29.4s-16.6 20.4-29.4 17c-10.9-2.9-21.1-4.9-30.2-5c-7.3-.1-14.7 1.7-19.4 4.4c-2.1 1.3-3.1 2.4-3.5 3c-.3 .5-.7 1.2-.7 2.8c0 .3 0 .5 0 .6c.2 .2 .9 1.2 3.3 2.6c5.8 3.5 14.4 6.2 27.4 10.1l.9 .3s0 0 0 0c11.1 3.3 25.9 7.8 37.9 15.3c13.7 8.6 26.1 22.9 26.4 44.9c.3 22.5-11.4 38.9-26.7 48.5c-6.7 4.1-13.9 7-21.3 8.8l0 10.6c0 13.3-10.7 24-24 24s-24-10.7-24-24l0-11.4c-9.5-2.3-18.2-5.3-25.6-7.8c-2.1-.7-4.1-1.4-6-2c-12.6-4.2-19.4-17.8-15.2-30.4s17.8-19.4 30.4-15.2c2.6 .9 5 1.7 7.3 2.5c13.6 4.6 23.4 7.9 33.9 8.3c8 .3 15.1-1.6 19.2-4.1c1.9-1.2 2.8-2.2 3.2-2.9c.4-.6 .9-1.8 .8-4.1l0-.2c0-1 0-2.1-4-4.6c-5.7-3.6-14.3-6.4-27.1-10.3l-1.9-.6c-10.8-3.2-25-7.5-36.4-14.4c-13.5-8.1-26.5-22-26.6-44.1c-.1-22.9 12.9-38.6 27.7-47.4c6.4-3.8 13.3-6.4 20.2-8.2L264 24c0-13.3 10.7-24 24-24s24 10.7 24 24zM568.2 336.3c13.1 17.8 9.3 42.8-8.5 55.9L433.1 485.5c-23.4 17.2-51.6 26.5-80.7 26.5L192 512 32 512c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l36.8 0 44.9-36c22.7-18.2 50.9-28 80-28l78.3 0 16 0 64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0-16 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l120.6 0 119.7-88.2c17.8-13.1 42.8-9.3 55.9 8.5zM193.6 384c0 0 0 0 0 0l-.9 0c.3 0 .6 0 .9 0z"/></svg></span> ' . carbon_get_the_post_meta('property_price') . ' USD</span></span></span>';
            endif;
                 
            echo '<span><a target="_blank" class="rdmlink" href="' . get_permalink() . '"> View Details </a></span></div>';
            echo '</div>';
        endwhile;
    } else {
        echo '<p>No properties found.</p>';
    }

    wp_die();
}

add_action('wp_ajax_properties_search', 'ajax_properties_search');
add_action('wp_ajax_nopriv_properties_search', 'ajax_properties_search');
