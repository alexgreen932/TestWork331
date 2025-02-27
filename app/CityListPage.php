<?php
class CityListPage {
    public function __construct() {
        add_filter('theme_page_templates', [$this, 'register_template']);
        add_filter('template_include', [$this, 'load_template']);
        add_action('wp_ajax_city_search', [$this, 'handle_ajax_search']);
        add_action('wp_ajax_nopriv_city_search', [$this, 'handle_ajax_search']);
    }

    public function register_template($templates) {
        $templates['city-list.php'] = __('City List Page', 'cities');
        return $templates;
    }

    public function load_template($template) {
        if (is_page_template('city-list.php')) {
            return get_stylesheet_directory() . '/templates/city-list.php';
        }
        return $template;
    }

    public static function get_cities($limit = 20, $search = '') {
        global $wpdb;
        $query = "SELECT p.ID, p.post_title, t.name AS country
                  FROM {$wpdb->posts} p
                  LEFT JOIN {$wpdb->term_relationships} tr ON (p.ID = tr.object_id)
                  LEFT JOIN {$wpdb->term_taxonomy} tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
                  LEFT JOIN {$wpdb->terms} t ON (tt.term_id = t.term_id)
                  WHERE p.post_type = 'city' 
                  AND p.post_status = 'publish' 
                  AND tt.taxonomy = 'country' ".
                  ($search ? "AND p.post_title LIKE %s " : "") . 
                  "ORDER BY p.post_title ASC 
                  LIMIT %d";
        
        return $wpdb->get_results($search ? $wpdb->prepare($query, '%' . $search . '%', $limit) : $wpdb->prepare($query, $limit));
    }

    public function handle_ajax_search() {
        $search = sanitize_text_field($_POST['search'] ?? '');
        $cities = self::get_cities(20, $search);
        wp_send_json($cities);
    }
}
