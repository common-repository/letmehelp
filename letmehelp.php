<?php
/**
 * Plugin Name:       LetMeHelp
 * Description:       This plugin helps to reduce support reqests, by showing possible help links before making a new support reqest.
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Version:           1.0.2
 * Author:            Taras Dashkevych
 * AuthorURI:         https://tarascodes.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       letmehelp
 *
 * @package           LetMeHelp
 */

// For security purposes
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Checks if the autoload file exists and includes it if it does.
 * This file is responsible for automatically loading classes for the plugin.
 */
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * The path to the plugin directory.
 *
 * @var string
 */
define( 'LETMEHELP_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The URL to the plugin directory.
 *
 * @var string
 */
define( 'LETMEHELP_URL', plugin_dir_url( __FILE__ ) );

/**
 * The plugin's basename.
 *
 * @var string
 */
define( 'LETMEHELP', plugin_basename( __FILE__ ) );

/**
 * The plugin's slug.
 *
 * @var string
 */
define( 'LETMEHELP_SLUG', 'letmehelp' );

/**
 * The plugin's version number.
 *
 * @var string LETMEHELP_VERSION
 */
define( 'LETMEHELP_VERSION', '1.0.2' );

use LetMeHelp\Base\Activate;
use LetMeHelp\Base\Deactivate;

if ( class_exists( 'LetMeHelp\\Init' ) ) {
	LetMeHelp\Init::register_services();
}

/**
 * Activates the LetMeHelp plugin.
 *
 * @return void
 */
function activate_letmehelp_plugin() {
	Activate::run();
}
register_activation_hook( __FILE__, 'activate_letmehelp_plugin' );

/**
 * Deactivates the LetMeHelp plugin.
 *
 * @return void
 */
function deactivate_letmehelp_plugin() {
	Deactivate::run();
}
register_deactivation_hook( __FILE__, 'deactivate_letmehelp_plugin' );
