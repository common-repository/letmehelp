<?php
/**
 * Functionality to be fired during plugin activation.
 *
 * @package LetMeHelp
 */
namespace LetMeHelp;

final class Init {

	/**
	 * Store all the classes
	 *
	 * @return array Full list of availible classes
	 */
	public static function get_services() {
		return array(
			Base\Api::class,
			Base\Enqueue::class,
			Blocks\Base::class,
			Base\SettingsLink::class,
			Pages\Admin::class,
		);
	}

	/**
	 * Loop through the classes, then create them
	 * and call register() method if exists
	 *
	 * @return void
	 */
	public static function register_services() {
		foreach ( self::get_services() as $class ) {
			$service = self::initialize( $class );

			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	/**
	 * Initialize class
	 *
	 * @param class $class  class from the services array
	 * @return class instance   new instance of the class
	 */
	private static function initialize( $class ) {
		$service = new $class();

		return $service;
	}
}
