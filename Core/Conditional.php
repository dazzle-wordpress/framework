<?php

/**
 * The conditional functionality of the plugin.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Classes
 * @subpackage Conditional
 */

namespace Dazzle\Fusion\Core;

use Dazzle\Fusion\Plugin;

/**
 * The conditional functionality of the plugin.
 *
 * Defines the conditional functions of the plugin.
 *
 * @package    Classes
 * @subpackage Conditional
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class Conditional
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
	 * is_woocommerce - Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes and thus are not included).
	 * @return bool
	 */
	function is_page() //is_woocommerce()
	{
		return apply_filters( $this->plugin->get_plugin_prefix() . '_is_page', ( $this->is_post_type() || $this->is_taxonomy() || $this->is_singular() ) ? true : false );
	}

	/**
	 * is_shop - Returns true when viewing the product type archive (shop).
	 * @return bool
	 */
	function is_post_type() //is_shop()
	{
		foreach ($this->plugin->config->get_post_type() as $posts => $post)
		{
			return ( is_post_type_archive( $post['post_type'] ) || is_page( $this->plugin->core->get_pages()->get_page_id( $post['post_type'] ) ) );
		}
	}

	/**
	 * is_product_taxonomy - Returns true when viewing a product taxonomy archive.
	 * @return bool
	 */
	function is_taxonomy() //is_product_taxonomy()
	{
		foreach ($this->plugin->config->get_post_type() as $posts => $post)
		{
			return is_tax( get_object_taxonomies( $post['post_type'] ) );
		}
	}

	/**
	 * is_product - Returns true when viewing a single product.
	 * @return bool
	 */
	function is_singular() //is_product()
	{
		foreach ($this->plugin->config->get_post_type() as $posts => $post)
		{
			return is_singular( [ $post['post_type'] ] );
		}
	}
}