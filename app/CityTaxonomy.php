<?php
class CityTaxonomy {

    public function __construct() {
        add_action('init', [$this, 'register']);
    }

    public function register() {
        $labels = [
            'name'              => __('Countries', 'cities'),
            'singular_name'     => __('Country', 'cities'),
            'search_items'      => __('Search Countries', 'cities'),
            'all_items'         => __('All Countries', 'cities'),
            'edit_item'         => __('Edit Country', 'cities'),
            'update_item'       => __('Update Country', 'cities'),
            'add_new_item'      => __('Add New Country', 'cities'),
            'new_item_name'     => __('New Country Name', 'cities'),
            'menu_name'         => __('Countries', 'cities')
        ];

        $args = [
            'labels'            => $labels,
            'public'            => true,
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'country']
        ];

        register_taxonomy('country', ['city'], $args);
    }
}
