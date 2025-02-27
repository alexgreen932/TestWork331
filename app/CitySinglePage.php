<?php
/**
 * Class CitySinglePage
 *
 * Custom rendering for single city pages.
 *
 * @package magic-jet
 */
class CitySinglePage {

    /**
     * Constructor to hook into WordPress filters.
     */
    public function __construct() {
        add_filter( 'the_content', array( $this, 'render_city_weather' ), 99 ); // High priority to override
    }

    /**
     * Renders only the city title and weather info.
     *
     * @param string $content Default post content.
     * @return string Modified content with only city title and weather.
     */
    public function render_city_weather( $content ) {
        global $post;

        // Ensure it's a single city post type.
        if ( is_singular( 'city' ) ) {
            $city_name = get_the_title( $post->ID );

            // Return only title and weather, removing all other content.
            return '<h1>' . esc_html( $city_name ) . '</h1>' . 
                   '<span data-city="' . esc_attr( $city_name ) . '"></span>';
        }

        return $content; // Return normal content for other post types
    }
}
