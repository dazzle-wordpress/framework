<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Dazzle\Fusion
 * @subpackage PluginName/includes
 */

namespace Dazzle\Fusion\Admin;

use Dazzle\Fusion\Plugin;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Dazzle\Fusion
 * @subpackage PluginName/includes
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class Uninstall
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
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

	}

}
