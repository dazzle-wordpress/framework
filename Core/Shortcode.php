<?php

/**
 * The shortcode of the plugin.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Classes
 * @subpackage Shortcode
 */

namespace Dazzle\Fusion\Core;

use Dazzle\Fusion\Plugin;

/**
 * The shortcode functionality of the plugin.
 *
 * Defines the plugin name, shortcodes.
 *
 * @package    Classes
 * @subpackage Shortcode
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class Shortcode
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
	 * Initialize the shortcodes and set its methods.
	 *
	 * @since 1.0.0
	 * 
	 * @param Fusion_Shortcode $shortcode This plugin's shortcode instance.
	 */
	public function add_post_shortcodes()
	{
		foreach ($this->plugin->config->get_shortcodes() as $shortcodes => $shortcode)
		{
			//echo print_r($shortcode, true);
		}
		//$this->plugin->get_post_shortcode()
	}

}
