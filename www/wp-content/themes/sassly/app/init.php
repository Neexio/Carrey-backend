<?php
namespace sassly;

/**
 *
 * This theme uses PSR-4 and OOP logic instead of procedural coding
 * Every function, hook and action is properly divided and organized inside related folders and files
 * 
 *
 * @package sassly
 */
final class Init
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services()
	{
		return [
			
			Core\Blog::class,
			Core\Tags::class,		
			Core\Wcf_Woo::class,
			Core\Blog_Widgets::class,
			Core\Theme_Setup::class,
			Core\Google_Fonts::class,
			Core\Enqueue::class,
			Core\InlineStyle::class,
			Core\Required_Plugins::class			
			
		];
	}

	/**
	 * Loop through the classes, initialize them, and call the register() method if it exists
	 * @return
	 */
	public static function register_services()
	{
		foreach ( self::get_services() as $class ) {
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register') ) {
				$service->register();
			}
		}
	}

	/**
	 * Initialize the class
	 * @param  class $class 		class from the services array
	 * @return class instance 		new instance of the class
	 */
	private static function instantiate( $class )
	{
		return new $class();
	}

}
