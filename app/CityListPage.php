<?php
/**
 * Class CityListPage
 *
 * Handles city listing, template registration, and AJAX search functionality.
 *
 * @package magic-jet
 */
class CityListPage {
    /**
     * CityListPage constructor.
     * Registers template filters and AJAX search handlers.
     */
    public function __construct() {
        add_filter( 'theme_page_templates', array( $this, 'register_template' ) );
        add_filter( 'template_include', array( $this, 'load_template' ) );
        add_action( 'wp_ajax_city_search', array( $this, 'handle_ajax_search' ) );
        add_action( 'wp_ajax_nopriv_city_search', array( $this, 'handle_ajax_search' ) );
    }

    /**
     * Registers the custom page template.
     *
     * @param array $templates Existing page templates.
     * @return array Modified page templates.
     */
    public function register_template( $templates ) {
        $templates['city-list.php'] = __( 'City List Page', 'cities' );
        return $templates;
    }

    /**
     * Loads the custom page template.
     *
     * @param string $template The path to the current template.
     * @return string The modified template path.
     */
    public function load_template( $template ) {
        if ( is_page_template( 'city-list.php' ) ) {
            return get_stylesheet_directory() . '/templates/city-list.php';
        }
        return $template;
    }

    /**
     * Retrieves a list of cities with optional search functionality.
     *
     * @param int $limit Number of results to fetch.
     * @param string $search Optional search term.
     * @return array List of city objects.
     */
    public static function get_cities( $limit = 20, $search = '' ) {
        global $wpdb;
        $query = "SELECT p.ID, p.post_title, t.name AS country
                  FROM {$wpdb->posts} p
                  LEFT JOIN {$wpdb->term_relationships} tr ON (p.ID = tr.object_id)
                  LEFT JOIN {$wpdb->term_taxonomy} tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
                  LEFT JOIN {$wpdb->terms} t ON (tt.term_id = t.term_id)
                  WHERE p.post_type = 'city' 
                  AND p.post_status = 'publish' 
                  AND tt.taxonomy = 'country' " .
                    ( $search ? 'AND p.post_title LIKE %s ' : '' ) .
                    'ORDER BY p.post_title ASC 
                  LIMIT %d';

        return $wpdb->get_results( $search ? $wpdb->prepare( $query, '%' . $search . '%', $limit ) : $wpdb->prepare( $query, $limit ) );
    }

    /**
     * Handles AJAX search requests and returns matching cities in JSON format.
     */
    public function handle_ajax_search() {
        $search = sanitize_text_field( $_POST['search'] ?? '' );
        $cities = self::get_cities( 20, $search );
        wp_send_json( $cities );
    }
}
