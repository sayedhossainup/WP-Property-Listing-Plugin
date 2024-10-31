<?php

function properties_search_filter_shortcode() {
    ob_start();
    ?>
    <h2 class="title-search-wrwpper"><?php echo get_option('properties_field_title'); ?></h2>
    <form id="properties-search-form">
        <input class="location comon-field w-45" type="text" name="location" placeholder="Location">
        <input class="location comon-field w-16" type="text" name="location_area" placeholder="Area">
        <select class="property_type w-16 comon-field" name="property_type">
            <option value="">Property Type</option>
            <?php
            // Get all terms from the 'property_type' taxonomy
            $property_types = get_terms([
                'taxonomy' => 'property_type',
                'hide_empty' => false, // Change to true to hide empty terms
            ]);

            if (!empty($property_types) && !is_wp_error($property_types)) {
                foreach ($property_types as $type) {
                    echo '<option value="' . esc_attr($type->slug) . '">' . esc_html($type->name) . '</option>';
                }
            }
            ?>
        </select>
        <input class="property_type w-16 comon-field" type="number" name="min_beds" placeholder="Bedrooms">
        <input class="property_type w-16 comon-field" type="number" name="min_baths" placeholder="Bathrooms">
        <input class="comon-field" type="number" name="min_price" placeholder="Min Price">
        <input class="comon-field" type="number" name="max_price" placeholder="Max Price">
        <button class="searchbtn <?php echo get_option('properties_form_submit'); ?>" type="submit">Search</button>
    </form>
    <div id="properties-search-results" class="bg-white"></div>
    <?php
    return ob_get_clean();
}

add_shortcode('properties_search_filter', 'properties_search_filter_shortcode');
