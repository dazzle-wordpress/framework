<?php

/**
 * Setup meta fields for wordpress posts or taxonomies.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Dazzle\Fusion\Admin
 * @subpackage Dazzle\Fusion\Admin\Menus
 */

namespace Dazzle\Fusion\Admin;

use Dazzle\Fusion\Plugin;

/**
 * Setup meta fields for wordpress posts or taxonomies.
 *
 * This class defines all code necessary to run during the plugin's posts or taxonomies.
 *
 * @since      1.0.0
 * @package    Dazzle\Fusion
 * @subpackage Dazzle\Fusion\Activator
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
	public function __construct( Plugin $plugin )
	{
		$this->plugin = $plugin;
	}
}
