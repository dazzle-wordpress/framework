<?php

/**
 * Fired during plugin activation
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Dazzle\Fusion
 * @subpackage Dazzle\Fusion\Activator
 */

namespace Dazzle\Fusion\Admin;

use Dazzle\Fusion\Plugin;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dazzle\Fusion
 * @subpackage Dazzle\Fusion\Activator
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class Install
{
	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin $plugin This plugin's instance.
	 */
	public $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * 
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin )
	{
		$this->plugin = $plugin;
		//init
	}

	/**
	 * Hook in tabs.
	 */
	public static function init()
	{
		//
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		flush_rewrite_rules(); //@todo move to its own class to deal with themes as well
	}

	/**
	 * Flush rewrite rules.
	 */
	public static function flush_rewrite_rules()
	{
		flush_rewrite_rules();
	}
}
