<?php
/**
 * Responsible for registering the plugin admin pages and functionality.
 *
 * @package LetMeHelp
 */
namespace LetMeHelp\Pages;

class Admin {

	/**
	 * Registers the plugin's admin page and menu.
	 * This function is hooked to the `admin_menu` action.
	 *
	 * @return void
	 */
	public function register() {
		// add admin page
		add_action( 'admin_menu', array( $this, 'admin_menu_pages' ) );
	}

	/**
	 * Registers the plugin's admin page and menu.
	 * This function is hooked to the `add_menu_page` action.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_menu_page/
	 *
	 * @return void
	 */
	public function admin_menu_pages() {
		add_menu_page(
			esc_html__( 'LetMeHelp Plugin', 'letmehelp' ), // The text to be displayed in the title tags of the page when the menu is selected.
			esc_html__( 'LetMeHelp', 'letmehelp' ), // The text to be used for the menu.
			'manage_options', // The capability required for this menu to be displayed to the user.
			LETMEHELP_SLUG, // The slug name to refer to this menu by (should be unique for this menu).
			function () {
				// The function to be called to output the content for this page.
				echo '<div id="letmehelp-app" class="letmehelp-app"></div>';
			},
			'dashicons-sos', // The icon to be used for this menu.
			null // The position in the menu order this one should appear.
		);
	}
}
