<?php
if (!defined('ABSPATH')) {
    exit;
}

function cpgi_add_image_to_product_gallery($gallery, $product_id) {
    $custom_image_id = get_option('cpgi_custom_image');
    $selected_categories = get_option('cpgi_selected_categories', []);
    $selected_tags = get_option('cpgi_selected_tags', []);

    if (!$custom_image_id) {
        return $gallery;
    }

    // Get product terms
    $product_categories = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'ids']);
    $product_tags = wp_get_post_terms($product_id, 'product_tag', ['fields' => 'ids']);

    $should_add_image = false;

    if (empty($selected_categories) && empty($selected_tags)) {
        $should_add_image = true;
    } else {
        if (!empty(array_intersect($product_categories, $selected_categories)) || 
            !empty(array_intersect($product_tags, $selected_tags))) {
            $should_add_image = true;
        }
    }

    if ($should_add_image) {
        $gallery[] = $custom_image_id;
    }

    return $gallery;
}

add_filter('woocommerce_product_gallery_attachment_ids', 'cpgi_add_image_to_product_gallery', 10, 2);
