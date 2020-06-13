<?php

/* enqueue scripts and style from parent theme */
add_action(
	'wp_enqueue_scripts',
	function() {
		wp_enqueue_style( 'parent', get_template_directory_uri() . '/style.css', [], '1.0' );
	}
);

// Customize settings modifications.
add_action(
	'customize_register',
	function ( WP_Customize_Manager $wp_customize ) {
		// Kill off twentytwenty's colour selection.
		$wp_customize->remove_section( 'colors' );
		
		$wp_customize->add_setting(
			'aben_maintenance_mode',
			array(
				'default' => false,
			)
		);

		$wp_customize->add_control(
			'aben_maintenance_mode',
			array(
				'label'    => __( 'Maintenance Mode', 'abstergo' ),
				'section'  => 'title_tagline',
				'settings' => 'aben_maintenance_mode',
				'type'     => 'checkbox',
				'std'      => '1'
			)
		);

		
	}
);

if( get_theme_mod('aben_maintenance_mode') && ( ! current_user_can( 'edit_themes' ) || ! is_user_logged_in() ) ) {
	add_action(
		'template_redirect',
		function() {
			?>
			<head>
				<link rel='stylesheet' id='maintenance-css'  href='<?php echo get_stylesheet_directory_uri() . '/style-maintenance.css'; ?>' media='all' />
				<title>Maintenance - Abstergo Industries</title>
			</head>
			<body>
				<div class="container">
					<img src="<?php echo trailingslashit( get_stylesheet_directory_uri() ) . 'img/abstergo/logo.svg'; ?>"/>
					<h1>We'll be back soon</h1>
					<p>This website has been taken down for maintenance, and will return shortly. We assure all customers of Abstergo Entertainment can continue to enjoy our titles while this website is offline.</p>
					<p class="subtle">&copy; <?php echo date("Y"); ?> - Abstergo Industries</p>
				</div>
			</body>
			<?php
			exit();
		},
		999
	);
}