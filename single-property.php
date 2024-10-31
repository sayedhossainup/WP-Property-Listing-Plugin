<?php
// single-property.php
get_header();

//if (have_posts()) : while (have_posts()) : the_post(); 
?>
<section class="single_property_wrapper">
    <div class="single_property_banner py-250 bg-dark" style="background-image: url(<?php echo wp_get_attachment_url(carbon_get_the_post_meta('property_main_image')); ?>)">
        <div class="property-container">
            <div class="title_wrwpper">
                <h1 class="text-white"><?php the_title(); ?></h1>
                <span class="address_property">
                    <span><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M9.00016 10.0726C10.2925 10.0726 11.3402 9.02492 11.3402 7.73258C11.3402 6.44023 10.2925 5.39258 9.00016 5.39258C7.70781 5.39258 6.66016 6.44023 6.66016 7.73258C6.66016 9.02492 7.70781 10.0726 9.00016 10.0726Z" stroke="#7F868E" stroke-width="1.5"/>
                            <path d="M2.71527 6.3675C4.19277 -0.127498 13.8153 -0.119998 15.2853 6.375C16.1478 10.185 13.7778 13.41 11.7003 15.405C10.1928 16.86 7.80777 16.86 6.29277 15.405C4.22277 13.41 1.85277 10.1775 2.71527 6.3675Z" stroke="#7F868E" stroke-width="1.5"/>
                        </svg></span>
                    <span class="text-gray"> <?php echo carbon_get_the_post_meta('property_location'); ?></span>
                </span>
            </div>

            <div class="price_wrwpper">
                <span class="wrap-title">Initial Price</span>
                <span class="property_price"> $ <?php echo carbon_get_the_post_meta('property_price'); ?></span>
                <div class="contact-btn"><a href="#">Contact Now</a></div>
                <div class="phone_number">call Direct<a href="tel:01753151615">01753 151615</a></div>
            </div>
        </div>
    </div>
    <div class="property-content">
        <?php the_content(); ?>

    </div>
</section>
<section class="single_property_wrapper py-80 bg-dark-of">
    <div class="property-container border-top">
        <div class="property_item">
            <span class="item_ele"><?php echo carbon_get_the_post_meta('property_suqer_area'); ?></span>
            <span class="ele-desc text-gray">Square Areas</span>
        </div>
        <div class="property_item">
            <span class="item_ele"><?php echo carbon_get_the_post_meta('property_beds'); ?></span>
            <span class="ele-desc text-gray">Bedroom</span>
        </div>
         <div class="property_item">
            <span class="item_ele"><?php echo carbon_get_the_post_meta('property_baths'); ?></span>
            <span class="ele-desc text-gray">Bathroom</span>
        </div>
        <div class="property_item">
            <span class="item_ele"><?php echo carbon_get_the_post_meta('property_carparking'); ?></span>
            <span class="ele-desc text-gray">Car Parking</span>
        </div>
    </div>
</section>

<?php
//endwhile; endif;

get_footer();
