<?php
class Metadata_Swap {
    private $meta_key;
    private $meta_value;
    private $meta_value_to_replace;
    private $post_ids = [];
    private $post_type;

    public function __construct($post_type, $meta_key, $meta_value, $meta_value_to_replace) {
        $this->meta_key = $meta_key;
        $this->meta_value = $meta_value;
        $this->meta_value_to_replace = $meta_value_to_replace;
        $this->post_type = $post_type;
    }

    public function get_posts() {
        $args = [
            'post_type' => $this->post_type,
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => $this->meta_key,
                    'value' => $this->meta_value,
                    'compare' => '=',
                ],
            ],
        ];
        $this->post_ids = array_map(function ($post) {
            return $post->ID;
        }, get_posts($args));

        return $this;
    }

    public function update_post_meta() {
        foreach ($this->post_ids as $post_id) {
            update_post_meta($post_id, $this->meta_key, $this->meta_value_to_replace);
        }
    }

    public function single_post_meta_update($post_id) {
        update_post_meta($post_id, $this->meta_key, $this->meta_value_to_replace);
    }
}
