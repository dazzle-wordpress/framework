<?php

/**
 * The public-facing functionality of the plugin pages.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Classes
 * @subpackage Pages
 */

namespace Dazzle\Fusion\Core;

use Dazzle\Fusion\Plugin;

/**
 * The public-facing functionality of the plugin pages.
 *
 * Defines the plugin pages.
 *
 * @package    Classes
 * @subpackage Pages
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class Pages
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
		add_filter( 'wp_nav_menu_objects', [ $this, 'nav_menu_item_classes' ], 2 );
		add_filter( 'wp_list_pages', [ $this, 'list_pages' ] );
	}

	/**
	 * Retrieve page ids returns -1 if no page is found.
	 *
	 * @param string $page
	 * @return int
	 */
	function get_page_id( $page )
	{
		$page = apply_filters( $this->plugin->get_plugin_prefix() . '_get_' . $page . '_page_id', get_option( $this->plugin->get_plugin_prefix() . '_' . $page . '_page_id' ) );
		return $page ? absint( $page ) : -1;
	}

	/**
	 * Retrieve page permalink.
	 *
	 * @param string $page
	 * @return string
	 */
	function get_page_permalink( $page )
	{
		$page_id   = $this->plugin->core->get_pages()->get_page_id( $page );
		$permalink = 0 < $page_id ? get_permalink( $page_id ) : get_home_url();
		return apply_filters( 'woocommerce_get_' . $page . '_page_permalink', $permalink );
	}

	/**
	 * Fix active class in nav for shop page.
	 *
	 * @param array $menu_items
	 * @return array
	 */
	function nav_menu_item_classes( $menu_items )
	{
		if ( ! $this->plugin->core->conditional->is_page() ) {
			return $menu_items;
		}

		$shop_page 		= (int) $this->plugin->core->get_pages()->get_page_id( 'shop' );
		$page_for_posts = (int) get_option( 'page_for_posts' );

		foreach ( (array) $menu_items as $key => $menu_item ) {

			$classes = (array) $menu_item->classes;

			// Unset active class for blog page
			if ( $page_for_posts == $menu_item->object_id ) {
				$menu_items[ $key ]->current = false;

				if ( in_array( 'current_page_parent', $classes ) ) {
					unset( $classes[ array_search( 'current_page_parent', $classes ) ] );
				}

				if ( in_array( 'current-menu-item', $classes ) ) {
					unset( $classes[ array_search( 'current-menu-item', $classes ) ] );
				}

			// Set active state if this is the shop page link
			} elseif ( $this->plugin->core->conditional->is_post_type() && $shop_page == $menu_item->object_id && 'page' === $menu_item->object ) {
				$menu_items[ $key ]->current = true;
				$classes[] = 'current-menu-item';
				$classes[] = 'current_page_item';

			// Set parent state if this is a product page
			} elseif ( is_singular( 'product' ) && $shop_page == $menu_item->object_id ) {
				$classes[] = 'current_page_parent';
			}

			$menu_items[ $key ]->classes = array_unique( $classes );

		}

		return $menu_items;
	}

	/**
	 * Fix active class in wp_list_pages for shop page.
	 *
	 * https://github.com/woocommerce/woocommerce/issues/177.
	 *
	 * @author Jessor, Peter Sterling
	 * @param string $pages
	 * @return string
	 */
	function list_pages( $pages )
	{
		if ( $this->plugin->core->conditional->is_page() )
		{
			// Remove current_page_parent class from any item.
			$pages = str_replace( 'current_page_parent', '', $pages );
			// Find shop_page_id through woocommerce options.
			$shop_page = 'page-item-' . $this->plugin->core->get_pages()->get_page_id( 'shop' );

			if ( $this->plugin->core->conditional->is_post_type() ) {
				// Add current_page_item class to shop page.
				$pages = str_replace( $shop_page, $shop_page . ' current_page_item', $pages );
			} else {
				// Add current_page_parent class to shop page.
				$pages = str_replace( $shop_page, $shop_page . ' current_page_parent', $pages );
			}
		}

		return $pages;
	}
}