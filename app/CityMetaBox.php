<?php
/**
 * Class CityMetaBox
 *
 * Handles the custom meta box for storing city latitude and longitude.
 *
 * @package magic-jet
 */
class CityMetaBox {

    /**
     * CityMetaBox constructor.
     * Adds actions for meta box registration and saving.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save_meta_data' ) );
    }

    /**
     * Registers the meta box for city location.
     */
    public function add_meta_box() {
        add_meta_box(
            'city_location_meta',
            __( 'City Location', 'cities' ),
            array( $this, 'render_meta_box' ),
            'city',
            'normal',
            'default'
        );
    }

    /**
     * Renders the meta box fields for latitude and longitude.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_meta_box( $post ) {
        $latitude  = get_post_meta( $post->ID, '_city_latitude', true );
        $longitude = get_post_meta( $post->ID, '_city_longitude', true );
        wp_nonce_field( 'city_location_nonce', 'city_location_nonce_field' );
        ?>
        <p>
            <label for="city_latitude">Latitude:</label>
            <input type="text" id="city_latitude" name="city_latitude" value="<?php echo esc_attr( $latitude ); ?>" />
        </p>
        <p>
            <label for="city_longitude">Longitude:</label>
            <input type="text" id="city_longitude" name="city_longitude" value="<?php echo esc_attr( $longitude ); ?>" />
        </p>
        <div id="auto-fill" class="button button-primary button-large">Fill Coordinates Automatically</div>
        <?php
    }

    /**
     * Saves the latitude and longitude metadata for a city.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_meta_data( $post_id ) {
        if ( ! isset( $_POST['city_location_nonce_field'] ) || ! wp_verify_nonce( $_POST['city_location_nonce_field'], 'city_location_nonce' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        if ( isset( $_POST['city_latitude'] ) ) {
            update_post_meta( $post_id, '_city_latitude', sanitize_text_field( $_POST['city_latitude'] ) );
        }
        if ( isset( $_POST['city_longitude'] ) ) {
            update_post_meta( $post_id, '_city_longitude', sanitize_text_field( $_POST['city_longitude'] ) );
        }
    }
}
