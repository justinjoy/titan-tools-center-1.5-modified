<?php
/**
 * Titan Tools center functions
 */

if ( ! function_exists( 'titan_tools_center_setup' ) ) :
function titan_tools_center_setup() {
    load_theme_textdomain( 'titan-tools-center', get_template_directory() . '/languages' );

	/**
	 * About Theme Function
	 */
	require get_theme_file_path( '/about-theme/class-titan-notice-handler.php' );
	
}
endif; 
add_action( 'after_setup_theme', 'titan_tools_center_setup' );

if ( ! function_exists( 'titan_tools_center_styles' ) ) :
	function titan_tools_center_styles() {
		// Register theme stylesheet.
		wp_register_style('titan-tools-center-style',
			get_template_directory_uri() . '/style.css',array(),
			wp_get_theme()->get( 'Version' )
		);


		// Enqueue Google Fonts
		wp_enqueue_style(
			'titan-tools-center-fonts',
			'https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;500;600;700;800;900&family=Lato:wght@300;400;700;900&family=Mulish:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&family=Quicksand:wght@300;400;500;600;700&family=Roboto:wght@200;300;400;500;600;700;800;900&family=Syne:wght@400;500;600;700;800&family=Urbanist:wght@300;400;500;600;700;800;900&display=swap',
			array(),
			null
		);

		// Enqueue Pretendard Font (Korean).
		wp_enqueue_style(
			'titan-tools-center-pretendard',
			'https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css',
			array(),
			'1.3.9'
		);

		// Enqueue Swiper CSS.
		wp_enqueue_style(
			'swiper-css',
			'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css',
			array(),
			'12.0.0'
		);
		
		// Enqueue Swiper JS.
		wp_enqueue_script(
			'swiper-js',
			'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js',
			array(), 
			'12.0.0', 
			true 
		);

		wp_enqueue_script('titan-tools-center-js',
        	get_template_directory_uri() . '/assets/js/effects.js',array(),
        	wp_get_theme()->get('Version'),true 
   		);

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'titan-tools-center-style' );

		wp_style_add_data( 'titan-tools-center-style', 'rtl', 'replace' );

	}
endif;
add_action( 'wp_enqueue_scripts', 'titan_tools_center_styles' );

/**
 * TGM FILE
 */
require get_theme_file_path( '/tgm.php' );

/**
 * Customizer
 */
require get_template_directory() . '/inc/customizer.php';