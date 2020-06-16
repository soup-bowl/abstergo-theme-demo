<?php
/**
 * Bob.
 *
 * @package abstergo-demo
 */

/* Enqueue scripts and style from parent theme. */
add_action(
	'wp_enqueue_scripts',
	function() {
		wp_enqueue_style( 'parent', get_template_directory_uri() . '/style.css', array(), '1' );
	}
);

/* Disable customizer colours. */
add_action(
	'wp_enqueue_scripts',
	function() {
		$styles = wp_styles();
		$styles->add_data( 'twentytwenty-style', 'after', array() );
	},
	20
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
				'std'      => '1',
			)
		);

	}
);

add_action(
	'login_enqueue_scripts',
	function() {
		wp_enqueue_style( 'aben-login', get_stylesheet_directory_uri() . '/style-login.css', array(), '2' );
		wp_enqueue_script( 'aben-login' );
	}
);

if ( get_theme_mod( 'aben_maintenance_mode' ) && ( ! current_user_can( 'edit_themes' ) || ! is_user_logged_in() ) ) {
	add_action(
		'template_redirect',
		function() {
			// Only shows if the site is disabled via style. Feel free to recommend/pull request a better method if really desired.
			// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
			?>
			<head>
				<link rel='stylesheet' id='maintenance-css'  href='<?php echo esc_url( get_stylesheet_directory_uri() . '/style-maintenance.css' ); ?>?ver=2' media='all' />
				<link rel="icon" href="<?php echo esc_url( get_site_icon_url( 32 ) ); ?>" sizes="32x32" />
				<link rel="icon" href="<?php echo esc_url( get_site_icon_url( 192 ) ); ?>" sizes="192x192" />
				<meta name="theme-color" content="#1c1c1c">
				<title>Maintenance - Abstergo Industries</title>
			</head>
			<body>
				<div class="nars-warning"><?php esc_html_e( 'This is not a real site. Trademark of all Assassin\'s creed content goes to Ubisoft.', 'aben' ); ?></div>
				<div class="container">
					<img src="<?php echo esc_url( trailingslashit( get_stylesheet_directory_uri() ) . 'img/abstergo/logo.svg' ); ?>"/>
					<h1>We'll be back soon</h1>
					<p>This website has been taken down for maintenance, and will return shortly. We assure all customers of Abstergo Entertainment can continue to enjoy our titles while this website is offline.</p>
					<p class="subtle">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> - Abstergo Industries</p>
				</div>
			</body>
			<?php
			// phpcs:enable
			exit();
		},
		999
	);
}
