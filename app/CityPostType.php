<?php

// dd("Class CityPostType loaded successfully - just checking out if it's working)))");
class CityPostType {

    public function __construct() {
        add_action('init', [$this, 'register']);
    }

    public function register() {
        $labels = [
            'name'               => __('Cities', 'cities'),
            'singular_name'      => __('City', 'cities'),
            'menu_name'          => __('Cities', 'cities'),
            'add_new'            => __('Add New', 'cities'),
            'add_new_item'       => __('Add New City', 'cities'),
            'edit_item'          => __('Edit City', 'cities'),
            'new_item'           => __('New City', 'cities'),
            'view_item'          => __('View City', 'cities'),
            'search_items'       => __('Search Cities', 'cities'),
            'not_found'          => __('No Cities found.', 'cities'),
            'not_found_in_trash' => __('No Cities found in Trash.', 'cities')
        ];

        $args = [
            'labels'              => $labels,
            'public'              => true,
            'show_ui'             => true,
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-location',
            'supports'            => ['title'],
            'has_archive'         => true,
            'rewrite'             => ['slug' => 'cities', 'with_front' => true],
            'capability_type'     => 'post',
            'show_in_rest'        => false
        ];

        register_post_type('city', $args);
    }
}
