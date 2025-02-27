<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package magic-jet
 * @since 1.0.0
 */
 
define( 'MAGICJET_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'ASSETS', get_stylesheet_directory_uri() . '/assets/' );//child theme assets directory

/**
 * Debugging function. For development only //todo rm on prod
 */
if ( ! function_exists( 'dd' ) ) {
	function dd( $var, $notdie = 0 ) {
		echo '<pre>';
		var_dump( $var );
		echo '</pre>';
		if ( $notdie == 0 ) {
			die();
		}
	}
}


if ( ! function_exists( 'jet_autoloader' ) ) {
	/**
	 * Automatically loads and initializes classes from a given directory.
	 *
	 * @param string $relative_path Directory path relative to theme root.
	 */
	function jet_autoloader( $relative_path ) {
		$paths = [
			get_stylesheet_directory() . '/' . trim( $relative_path, '/' ),
			get_template_directory() . '/' . trim( $relative_path, '/' ),
		];

		foreach ( $paths as $path ) {
			if ( ! is_dir( $path ) ) {
				continue;
			}

			foreach ( glob( $path . '/*.php' ) as $file ) {
				$class_name = pathinfo( $file, PATHINFO_FILENAME );

				require_once $file;

				// Check if the class with the same name as the file exists
				if ( class_exists( $class_name ) ) {
					new $class_name();
				}
			}
		}
	}
}

// load classes from a necessary directory(s)
jet_autoloader( 'app' );

//unque styles
function magicjet_enqueue_styles() {

	wp_enqueue_style(
		'jet',
		ASSETS . 'css/style.min.css',
		array(),
		MAGICJET_VERSION // Replace with your version if needed
	);
    
    wp_enqueue_script(
		'jet',
		ASSETS . 'js/script.min.js',
		array(),
		MAGICJET_VERSION,
		true
	);
}

add_action( 'wp_enqueue_scripts','magicjet_enqueue_styles' );






