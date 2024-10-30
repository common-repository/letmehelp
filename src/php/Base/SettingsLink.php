<?php
/**
 * SettingsLink class file.
 *
 * This file contains the SettingsLink class, which is responsible for registering
 * a settings link for the LetMeHelp plugin.
 *
 * @package LetMeHelp
 */

namespace LetMeHelp\Base;

/**
 * SettingsLink class.
 *
 * This class is responsible for registering a settings link for the LetMeHelp plugin.
 */
class SettingsLink {
	/**
	 * Registers the settings link.
	 */
	public function register() {
		add_filter( 'plugin_action_links_' . LETMEHELP, array( $this, 'settings_link' ) );
	}

	/**
	 * Generates the settings link.
	 *
	 * @param array $links The existing plugin action links.
	 *
	 * @return array The updated plugin action links.
	 */
	public function settings_link( $links ) {
		$settings_link = sprintf(
			'<a href="admin.php?page=%1$s">%2$s</a>',
			LETMEHELP_SLUG,
			esc_html__( 'Settings' )
		);

		array_push( $links, $settings_link );
		return $links;
	}
}
