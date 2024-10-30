<?php
/**
 * Load scripts and styles functionality.
 *
 * @package LetMeHelp
 */
namespace LetMeHelp\Base;

class Enqueue {

	/**
	 * Register scripts and styles to be enqueued on the appropriate pages.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueues scripts and styles for the plugin's admin settings page.
	 *
	 * @param string $hook The current admin page hook.
	 * @return void
	 */
	public function admin_scripts( $hook ) {
		// Load only on plugin's page.
		if ( 'toplevel_page_letmehelp' !== $hook ) {
			return;
		}

		// Scripts dependency files.
		$asset_file = LETMEHELP_PATH . 'build/settings/index.asset.php';
		// Fallback dependency array.
		$dependency = array();
		$version    = LETMEHELP_VERSION;

		// Set dependency and version.
		if ( file_exists( $asset_file ) ) {
			$asset_file = include $asset_file;         // phpcs:ignore
			$dependency = $asset_file['dependencies']; // phpcs:ignore
			$version    = $asset_file['version'];
		}

		// Load our app.js.
		wp_register_script(
			'letmehelp-admin-settings',
			LETMEHELP_URL . 'build/settings/index.js',
			$dependency,
			$version,
			true
		);
		wp_enqueue_script( 'letmehelp-admin-settings' );

		// Add the wp_localize_script function below the wp_enqueue_script
		wp_localize_script(
			'letmehelp-admin-settings',
			'letmehelpApiSettings',
			array(
				'nonce' => wp_create_nonce( 'wp_rest' ),
			)
		);

		// Enqueue styles.
		wp_enqueue_style(
			'letmehelp-admin-settings',
			LETMEHELP_URL . 'build/settings/style-index.css',
			array( 'wp-components' ),
			$version
		);
	}

	/**
	 * Enqueue scripts and styles for the public-facing pages.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Register and enqueue public styles.
		wp_register_style(
			'letmehelp-public-base',
			LETMEHELP_URL . 'build/public/css/base.css',
			array(),
			'1.0.0'
		);

		wp_enqueue_style( 'letmehelp-public-base' );

		// Register and enqueue public scripts.
		wp_register_script(
			'letmehelp-public-base',
			LETMEHELP_URL . 'build/public/js/base.js',
			array(),
			'1.0.0',
			true
		);

		// Add the wp_localize_script function below the wp_enqueue_script.
		wp_localize_script(
			'letmehelp-public-base',
			'letmehelpApiPublic',
			array(
				'restSearchKeywordsUrl' => get_rest_url( null, 'letmehelp/v1/search-links/' ),
			)
		);
	}
}
