<?php



/* ---------------------------------------------------------------------------

 * Child Theme URI | DO NOT CHANGE

 * --------------------------------------------------------------------------- */

define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );





/* ---------------------------------------------------------------------------

 * Define | YOU CAN CHANGE THESE

 * --------------------------------------------------------------------------- */



// White Label --------------------------------------------

define( 'WHITE_LABEL', false );



// Static CSS is placed in Child Theme directory ----------

define( 'STATIC_IN_CHILD', false );





/* ---------------------------------------------------------------------------

 * Enqueue Style

 * --------------------------------------------------------------------------- */

add_action( 'wp_enqueue_scripts', 'mfnch_enqueue_styles', 101 );

function mfnch_enqueue_styles() {

	

	// Enqueue the parent stylesheet

// 	wp_enqueue_style( 'parent-style', get_template_directory_uri() .'/style.css' );		//we don't need this if it's empty

	

	// Enqueue the parent rtl stylesheet

	if ( is_rtl() ) {

		wp_enqueue_style( 'mfn-rtl', get_template_directory_uri() . '/rtl.css' );

	}

	

	// Enqueue the child stylesheet

	wp_dequeue_style( 'style' );

	wp_enqueue_style( 'style', get_stylesheet_directory_uri() .'/style.css' );

	

}


function my_custom_search_style() {
    return 'blog';
}
add_filter( 'wpex_search_results_style', 'my_custom_search_style' );
function vc_excerpt_fix($expt) {

  $fixed_excerpt = preg_replace( '/\[[^\]]+\]/', '', $expt);  # strip shortcodes, keep shortcode content

  return $fixed_excerpt;

}

add_filter( 'rpwe_excerpt', 'vc_excerpt_fix'  );





add_filter('relevanssi_excerpt_content', 'rlv_strip_shortcodes');

function rlv_strip_shortcodes($excerpt) {

    return strip_shortcodes($excerpt);

}



add_filter('relevanssi_pre_excerpt_content', 'rlv_trim_divi_shortcodes');

function rlv_trim_divi_shortcodes($content) {

    $content = preg_replace('/\[\/?et_pb.*?\]/', '', $content);

    return $content;

}

/* ---------------------------------------------------------------------------

 * Load Textdomain

 * --------------------------------------------------------------------------- */

add_action( 'after_setup_theme', 'mfnch_textdomain' );

function mfnch_textdomain() {

    load_child_theme_textdomain( 'betheme',  get_stylesheet_directory() . '/languages' );

    load_child_theme_textdomain( 'mfn-opts', get_stylesheet_directory() . '/languages' );

}


?>