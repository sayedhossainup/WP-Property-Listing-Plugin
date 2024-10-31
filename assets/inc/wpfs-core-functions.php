<?php

/*
 *
 * 	***** WP Property for sale *****
 *
 * 	Core Functions
 * 	
 */
// If this file is called directly, abort. //
if (!defined('WPINC')) {
    die;
} // end if

function register_properties_post_type() {
    register_post_type('property', [
        'labels' => [
            'name' => 'Properties',
            'singular_name' => 'Property',
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'properties'],
        'supports' => ['title', 'thumbnail'],
    ]);
    // Register the Property Type taxonomy (non-hierarchical)
    register_taxonomy('property_type', 'property', [
        'labels' => [
            'name' => 'Property Types',
            'singular_name' => 'Property Type',
        ],
        'hierarchical' => true, // Works like tags
        'public' => true,
        'rewrite' => ['slug' => 'property-type'],
    ]);
}

add_action('init', 'register_properties_post_type');

function activate_properties_plugin() {
    register_properties_post_type();
    flush_rewrite_rules(); // Clear permalinks to ensure the custom post type appears.
}

register_activation_hook(__FILE__, 'activate_properties_plugin');

/*
 *
 * Custom Front End Ajax Scripts / Loads In WP Footer
 *
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

function crb_load_carbon_fields() {
    \Carbon_Fields\Carbon_Fields::boot();
}

add_action('after_setup_theme', 'crb_load_carbon_fields');

function add_properties_meta_fields() {
    Container::make('post_meta', 'Property Details')
            ->where('post_type', '=', 'property')
            ->add_fields([
                Field::make('text', 'property_price', 'Price')
                ->set_attribute('type', 'number')
                ->set_help_text('Enter the property price in USD.'),
                Field::make('text', 'property_location', 'Location')
                ->set_help_text('Enter the location of the property.'),
                 Field::make('text', 'property_location_area', 'Location Area'),
                Field::make('text', 'property_suqer_area', 'Square Area'),
                Field::make('text', 'property_beds', 'Bedrooms')
                ->set_help_text('Number of Bedrooms.'),
                Field::make('text', 'property_baths', 'Bathrooms')
                ->set_help_text('Number of Bathrooms.'),
                Field::make('radio', 'property_carparking', __('Car Parking'))
                ->add_options(array(
                    'yes' => __('Yes'),
                    'no' => __('No'),                    
                )),
                Field::make('textarea', 'property_description', 'Description')
                ->set_help_text('Provide a brief description of the property.'),
                Field::make('image', 'property_main_image', __('Property Banner Image'))
    ]);
}

add_action('carbon_fields_register_fields', 'add_properties_meta_fields');

