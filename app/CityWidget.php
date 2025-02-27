<?php
class CityWidget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'city_weather_widget',
            __('City Weather', 'cities'),
            ['description' => __('Displays weather for a selected city.', 'cities')]
        );
        add_action('widgets_init', function() {
            register_widget('CityWidget');
        });
    }

    public function widget($args, $instance) {
        $city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';
        if (!$city_id) return;

        $city_name = get_the_title($city_id);
        $latitude = get_post_meta($city_id, '_city_latitude', true);
        $longitude = get_post_meta($city_id, '_city_longitude', true);
        
        $weather = $this->fetch_weather($latitude, $longitude);

        echo $args['before_widget'];
        echo $args['before_title'] . esc_html($city_name) . $args['after_title'];
        echo '<p>Temperature: ' . esc_html($weather) . '°C</p>';
        echo $args['after_widget'];
    }

    public function form($instance) {
        $city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';
        $cities = get_posts(['post_type' => 'city', 'numberposts' => -1]);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('city_id')); ?>">Select City:</label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('city_id')); ?>" name="<?php echo esc_attr($this->get_field_name('city_id')); ?>">
                <?php foreach ($cities as $city) { ?>
                    <option value="<?php echo esc_attr($city->ID); ?>" <?php selected($city->ID, $city_id); ?>><?php echo esc_html($city->post_title); ?></option>
                <?php } ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['city_id'] = !empty($new_instance['city_id']) ? sanitize_text_field($new_instance['city_id']) : '';
        return $instance;
    }

    private function fetch_weather($latitude, $longitude) {
        $api_key = 'YOUR_OPENWEATHERMAP_API_KEY';
        $url = "https://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&units=metric&appid={$api_key}";
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) return 'N/A';
        
        $data = json_decode(wp_remote_retrieve_body($response), true);
        return $data['main']['temp'] ?? 'N/A';
    }
}
