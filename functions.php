<?php

/* enqueue scripts and style from parent theme */
add_action(
	'wp_enqueue_scripts',
	function() {
		wp_enqueue_style( 'parent', get_template_directory_uri() . '/style.css', [], '1.0' );
	}
);

// Kill off twentytwenty's colour selection.
function sbat_remove_colour( $wp_customize ) {
	$wp_customize->remove_section( 'colors' );
}
add_action( 'customize_register', 'sbat_remove_colour' );