<?php

/**
 * The public-facing meta fields functionality of the plugin.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Core
 * @subpackage Metabox
 */

namespace Dazzle\Fusion\Core;

use Dazzle\Fusion\Plugin;

/**
 * The public-facing meta fields functionality of the plugin.
 *
 * Defines the plugin meta fields, and admin metabox fields in the post type or taxonomy.
 *
 * @package    Core
 * @subpackage Metabox
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class Metabox
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
}
