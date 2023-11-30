<?php
/*
Plugin Name: Site Favicon
Description: Add a favicon.
Version: 0.2
Requires at least: 5.0
Author: Bryan Hadaway
Author URI: https://calmestghost.com/
License: Public Domain
License URI: https://wikipedia.org/wiki/Public_domain
Text Domain: site-favicon
*/

if ( !defined( 'ABSPATH' ) ) {
	http_response_code( 404 );
	die();
}

add_action( 'wp_head', 'sitefavicon_add_custom', 100 );
function sitefavicon_add_custom() {
	echo '<link rel="icon" href="' . get_option( 'favicon', '' ) . '" sizes="32x32">';
}

add_action( 'customize_register', 'sitefavicon_customizer_setting' );
function sitefavicon_customizer_setting( $wp_customize ) {
	$wp_customize->add_setting(
		'favicon',
		array(
			'default'    => '',
			'type' 		 => 'option',
			'capability' => 'edit_theme_options'
		),
	);
	$wp_customize->add_control( new WP_Customize_Control(
		$wp_customize,
		'favicon',
		array(
			'label'	  	  => __( 'Site Favicon', 'site-favicon' ),
			'description' => __( 'Add favicon (32x32 recommended).', 'site-favicon' ),
			'settings'    => 'favicon',
			'priority'    => 15,
			'section'	  => 'title_tagline',
			'type'	      => 'url'
		)
	) );
}