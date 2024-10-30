<?php
/**
 * Functions for uninstall plugin.
 *
 * @package
 */

// if uninstall.php is not called by WordPress, die.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

/**
 * Checks if the autoload file exists and includes it if it does.
 * This file is responsible for automatically loading classes for the plugin.
 */
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

use LetMeHelp\Base\Database;

// Drop custom options.
Database::delete_options();
// Drop custom tables.
Database::drop_tables();
