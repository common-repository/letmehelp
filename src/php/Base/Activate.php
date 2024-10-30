<?php
/**
 * Functionality to be fired during plugin activation.
 *
 * @package LetMeHelp
 */
namespace LetMeHelp\Base;

use LetMeHelp\Base\Database;

/**
 * The Activate class is responsible for activating the plugin.
 */
class Activate {
	/**
	 * Runs when the plugin is activated and creates necessary database tables.
	 *
	 * @return void
	 */
	public static function run() {
		Database::maybe_create_tables();
	}
}
