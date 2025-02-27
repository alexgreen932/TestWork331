<?php
/**
 * Class CityPostType
 *
 * Registers the custom post type "City".
 *
 * @package magic-jet
 */
class CityPostType {

    /**
     * CityPostType constructor.
     * Hooks into WordPress init to register the custom post type.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register' ) );
    }

    /**
     * Registers the "City" custom post type.
     */
    public function register() {
        $labels = array(
            'name'               => __( 'Cities', 'cities' ),
            'singular_name'      => __( 'City', 'cities' ),
            'menu_name'          => __( 'Cities', 'cities' ),
            'add_new'            => __( 'Add New', 'cities' ),
            'add_new_item'       => __( 'Add New City', 'cities' ),
            'edit_item'          => __( 'Edit City', 'cities' ),
            'new_item'           => __( 'New City', 'cities' ),
            'view_item'          => __( 'View City', 'cities' ),
            'search_items'       => __( 'Search Cities', 'cities' ),
            'not_found'          => __( 'No Cities found.', 'cities' ),
            'not_found_in_trash' => __( 'No Cities found in Trash.', 'cities' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true, // ✅ Allows single city pages to work
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true, // ✅ Enables ?city=london queries
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-location',
            'supports'           => array( 'title' ),
            'has_archive'        => true,
            'rewrite'            => array(
                'slug'       => 'cities',
                'with_front' => true,
            ),
            'capability_type' => 'post',
            'show_in_rest'    => true, // ✅ Enables REST API for this post type
            'rest_base'       => 'cities', // ✅ Custom endpoint for REST API
        );

        register_post_type( 'city', $args );

        // Flush permalinks when the post type is registered
        add_action( 'init', function() {
            flush_rewrite_rules();
        }, 20 );
    }
}

