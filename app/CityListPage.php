<?php
class CityListPage {

    public function __construct() {
        add_action('init', [$this, 'register_template']);
        add_action('wp_ajax_city_search', [$this, 'handle_ajax_search']);
        add_action('wp_ajax_nopriv_city_search', [$this, 'handle_ajax_search']);
    }

    public function register_template() {
        add_filter('template_include', function ($template) {
            if (is_page_template('city-list.php')) {
                return get_stylesheet_directory() . '/city-list.php';
            }
            return $template;
        });
    }

    public function handle_ajax_search() {
        global $wpdb;
        $search = sanitize_text_field($_POST['search'] ?? '');
        $query = "SELECT p.ID, p.post_title FROM {$wpdb->posts} p WHERE p.post_type = 'city' AND p.post_status = 'publish' AND p.post_title LIKE %s";
        $results = $wpdb->get_results($wpdb->prepare($query, '%' . $search . '%'));
        wp_send_json($results);
    }
}
