<?php 
function delete_posts_by_meta($post_type, $meta_key, $meta_value) {
    // Create a WP_Query with meta_query
    $args = array(
        'post_type' => $post_type,
        'meta_query' => array(
            array(
                'key' => $meta_key,
                'value' => $meta_value,
                'compare' => '=',
            ),
        ),
    );

    $query = new WP_Query($args);

    // Check if there are posts
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            // Delete the post
            wp_delete_post($post_id, true);
        }

        // Reset post data
        wp_reset_postdata();
    }
}
