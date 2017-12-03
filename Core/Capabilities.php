<?php

/**
 * The capabilities of the plugin.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Classes
 * @subpackage Capabilities
 */

namespace Dazzle\Fusion\Core;

use Dazzle\Fusion\Plugin;

/**
 * The capabilities functionality of the plugin.
 *
 * Defines the plugin name, capabilities.
 *
 * @package    Classes
 * @subpackage Capabilities
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class Capabilities
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
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Initialize the capabilities and set its methods.
	 *
	 * @since 1.0.0
	 * 
	 * @param Fusion_Capabilities $capability This plugin's capability instance.
	 */
	public function add_post_capabilities()
	{
		foreach ($this->plugin->config->get_capabilities() as $capabilities => $capability)
		{
			//echo print_r($capability, true);
		}
		//$this->plugin->get_post_shortcode()
	}

}
