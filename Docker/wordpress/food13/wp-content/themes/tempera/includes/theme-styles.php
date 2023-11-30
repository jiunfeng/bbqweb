<?php
/*
 * Styles and scripts registration and enqueuing
 *
 * @package tempera
 * @subpackage Functions
 */

/**
 * Enqueue all the styles 
 */
function tempera_enqueue_styles() {
	global $temperas;
	extract($temperas);

	wp_enqueue_style( 'tempera-fonts', get_template_directory_uri() . '/fonts/fontfaces.css', NULL, _CRYOUT_THEME_VERSION ); // fontfaces.css

	/* Google fonts */
	$gfonts = array();
	
	if ( ( $tempera_fontfamily != 'font-custom' ) && !empty( $tempera_googlefont ) ) 				$gfonts[] = cryout_gfontclean( $tempera_googlefont );
	if ( ( $tempera_fonttitle != 'font-custom' ) && !empty( $tempera_googlefonttitle ) ) 			$gfonts[] = cryout_gfontclean( $tempera_googlefonttitle );
	if ( ( $tempera_fontside != 'font-custom' ) && !empty( $tempera_googlefontside ) ) 				$gfonts[] = cryout_gfontclean( $tempera_googlefontside );
	if ( ( $tempera_headingsfont != 'font-custom' ) && !empty( $tempera_headingsgooglefont ) )		$gfonts[] = cryout_gfontclean( $tempera_headingsgooglefont );
	if ( ( $tempera_sitetitlefont != 'font-custom' ) && !empty( $tempera_sitetitlegooglefont ) )	$gfonts[] = cryout_gfontclean( $tempera_sitetitlegooglefont );
	if ( ( $tempera_menufont != 'font-custom' ) && !empty( $tempera_menugooglefont ) ) 				$gfonts[] = cryout_gfontclean( $tempera_menugooglefont );

	// enqueue fonts with subsets separately
	foreach ( $gfonts as $i=>$gfont ) {
		if (strpos($gfont,"&") !== false) {
			wp_enqueue_style( 'tempera-googlefont_'.$i, '//fonts.googleapis.com/css?family=' . $gfont );
			unset($gfonts[$i]);
		}
	}

	// merged google fonts
	if ( count($gfonts)>0 ) {
		wp_enqueue_style( 'tempera-googlefonts', '//fonts.googleapis.com/css?family=' . implode( "|" , array_unique($gfonts) ), array(), null, 'screen' ); // google font
	};

	// Main theme style
	wp_enqueue_style( 'tempera-style', get_stylesheet_uri(), NULL, _CRYOUT_THEME_VERSION ); // main style.css
	
	// Options-based generated styling
 	wp_add_inline_style( 'tempera-style', preg_replace( "/[\n\r\t\s]+/", " ", tempera_custom_styles() ) ); // includes/custom-styles.php

	// Presentation Page options-based styling (only used when needed)
	if ( ($tempera_frontpage=="Enable") && is_front_page() && ('posts' == get_option( 'show_on_front' )) ) {
		wp_add_inline_style( 'tempera-style', preg_replace( "/[\n\r\t\s]+/", " ", tempera_presentation_css() ) ); // also in includes/custom-styles.php
	}

	// RTL support
	if ( is_rtl() ) wp_enqueue_style( 'tempera-rtl', get_template_directory_uri() . '/styles/rtl.css', NULL, _CRYOUT_THEME_VERSION );	
	
	// User supplied custom styling
	wp_add_inline_style( 'tempera-style', preg_replace( "/[\n\r\t\s]+/", " ", tempera_customcss() ) ); // also in includes/custom-styles.php   
	
	// Responsive styling (loaded last)
	if ( $tempera_mobile=="Enable" ) {
	    wp_enqueue_style( 'tempera-mobile', get_template_directory_uri() . '/styles/style-mobile.css', NULL, _CRYOUT_THEME_VERSION  );
	}
	
} // tempera_enqueue_styles()
add_action( 'wp_enqueue_scripts', 'tempera_enqueue_styles' );


/**
 * Custom JS 
 */
add_action( 'wp_footer', 'tempera_customjs', 35 ); // includes/custom-styles.php


/**
 * Frontend scripts 
 */
function tempera_scripts_method() {
	global $temperas;

	wp_enqueue_script('tempera-frontend', get_template_directory_uri() . '/js/frontend.js', array('jquery'), _CRYOUT_THEME_VERSION, true );

	if (($temperas['tempera_frontpage'] == "Enable") && is_front_page() && !is_page()) {
		// if PP and the current page is frontpage - load the nivo slider js
		wp_enqueue_script('tempera-nivoslider', get_template_directory_uri() . '/js/nivo.slider.min.js', array('jquery'), _CRYOUT_THEME_VERSION, true);
		// add slider init js in footer
		add_action('wp_footer', 'tempera_pp_slider' ); // frontpage.php
	}
	
	$js_options = array(
		'mobile' => ( ($temperas['tempera_mobile']=='Enable') ? 1 : 0 ),
		'fitvids' => $temperas['tempera_fitvids'],
		'contentwidth' => $temperas['tempera_sidewidth'],
	);
	//wp_localize_script( 'tempera-frontend', 'cryout_global_content_width', $temperas['tempera_sidewidth'] );
	wp_localize_script( 'tempera-frontend', 'tempera_settings', $js_options );

	// Support sites with threaded comments (when in use)
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
} // tempera_scripts_method()
if ( !is_admin() ) add_action( 'wp_enqueue_scripts', 'tempera_scripts_method' ); 

/**
 * Customize editor styling a bit
 * tempera_custom_editor_styles() is located in custom-styles.php
 */
function tempera_add_editor_styles() {
	add_editor_style( add_query_arg( 'action', 'tempera_editor_styles', admin_url( 'admin-ajax.php' ) ) );
	add_action( 'wp_ajax_tempera_editor_styles', 'tempera_editor_styles' );
	add_action( 'wp_ajax_no_priv_tempera_editor_styles', 'tempera_editor_styles' );
} // tempera_add_editor_styles()
if ( is_admin() && $temperas['tempera_editorstyle'] ) tempera_add_editor_styles();

// FIN