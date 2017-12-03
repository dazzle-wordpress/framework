<?php

/**
 * Setup menus for wordpress administration.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Dazzle\Fusion\Admin
 * @subpackage Dazzle\Fusion\Admin\Menus
 */

namespace Dazzle\Fusion\Admin;

use Dazzle\Fusion\Plugin;
use Dazzle\Fusion\Admin\Settings as Options;

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
class Menus
{
	/**
	 * Hook in tabs.
	 */
	public function __construct( Plugin $plugin )
	{
		$this->plugin = $plugin;
		// Add menus
		add_action( 'admin_menu', [ $this, 'admin_menu' ], 9 );
		add_action( 'admin_menu', [ $this, 'reports_menu' ], 20 );
		add_action( 'admin_menu', [ $this, 'settings_menu' ], 50 );
		add_action( 'admin_menu', [ $this, 'status_menu' ], 60 );

		add_action( 'admin_head', [ $this, 'menu_order_count' ] );
		add_filter( 'menu_order', [ $this, 'menu_order' ] );
		add_filter( 'custom_menu_order', [ $this, 'custom_menu_order' ] );

		// Admin bar menus
		if ( apply_filters( $this->plugin->get_plugin_prefix() . '_show_admin_bar_visit_store', true ) ) {
			add_action( 'admin_bar_menu', [ $this, 'admin_bar_menus' ], 31 );
		}
	}

	/**
	 * Add menu items.
	 */
	public function admin_menu() {
		global $menu;
		//if ( current_user_can( 'manage_woocommerce' ) ) {
			$menu[] = [ '', 'read', 'separator-woocommerce', '', 'wp-menu-separator woocommerce' ];
		//}
//manage_woocommerce
		add_menu_page( __( 'WooCommerce2', $this->plugin->get_plugin_name() ), __( 'WooCommerce2', $this->plugin->get_plugin_name() ), 'manage_options', 'woocommerce', null, null, '55.5' );

		add_submenu_page( 'edit.php?post_type=book', __( 'Attributes', $this->plugin->get_plugin_name() ), __( 'Attributes', $this->plugin->get_plugin_name() ), 'manage_product_terms', 'product_attributes', [ $this, 'attributes_page' ] );
	}

	/**
	 * Add menu item.
	 */
	public function reports_menu() {
		if ( current_user_can( 'manage_woocommerce' ) ) {
			add_submenu_page( 'woocommerce', __( 'Reports', $this->plugin->get_plugin_name() ),  __( 'Reports', $this->plugin->get_plugin_name() ) , 'view_woocommerce_reports', 'wc-reports', [ $this, 'reports_page' ] );
		} else {
			add_menu_page( __( 'Sales reports', $this->plugin->get_plugin_name() ),  __( 'Sales reports', $this->plugin->get_plugin_name() ) , 'view_woocommerce_reports', 'wc-reports', [ $this, 'reports_page' ], null, '55.6' );
		}
	}

	/**
	 * Add menu item.
	 */
	public function settings_menu()
	{
		$settings = $this->plugin->config->get_settings();
		$settings_page = add_submenu_page( $settings['parent_slug'], __( $settings['page_title'], $this->plugin->get_plugin_name() ), __( $settings['menu_title'], $this->plugin->get_plugin_name() ) , $settings['capability'], $settings['menu_slug'], [ $this, $settings['callable'] ] );
		add_action( 'load-' . $settings_page, [ $this, 'settings_page_init' ] );
	}

	/**
	 * Loads gateways and shipping methods into memory for use within settings.
	 */
	public function settings_page_init()
	{
		global $current_tab, $current_section;

		// Get current tab/section
		$current_tab     = empty( $_GET['tab'] ) ? 'general' : sanitize_title( $_GET['tab'] );
		$current_section = empty( $_REQUEST['section'] ) ? '' : sanitize_title( $_REQUEST['section'] );

		// Save settings if data has been posted
		if ( ! empty( $_POST ) )
		{
			$this->plugin->admin->settings->save();
		}

		// Add any posted messages
		if ( ! empty( $_GET['wc_error'] ) )
		{
			$this->plugin->admin->settings->add_error( stripslashes( $_GET['wc_error'] ) );
		}

		if ( ! empty( $_GET['wc_message'] ) )
		{
			$this->plugin->admin->settings->add_message( stripslashes( $_GET['wc_message'] ) );
		}
	}

	/**
	 * Add menu item.
	 */
	public function status_menu() {
		add_submenu_page( 'woocommerce', __( 'WooCommerce status', $this->plugin->get_plugin_name() ),  __( 'Status', $this->plugin->get_plugin_name() ) , 'manage_woocommerce', 'wc-status', [ $this, 'status_page' ] );
	}

	/**
	 * Adds the order processing count to the menu.
	 */
	public function menu_order_count() {
		global $submenu;

		if ( isset( $submenu['woocommerce'] ) ) {
			// Remove 'WooCommerce' sub menu item
			unset( $submenu['woocommerce'][0] );

			// Add count if user has access
			if ( apply_filters( $this->plugin->get_plugin_prefix() . '_include_processing_order_count_in_menu', true ) && current_user_can( 'manage_woocommerce' ) && ( $order_count = wc_processing_order_count() ) ) {
				foreach ( $submenu['woocommerce'] as $key => $menu_item ) {
					if ( 0 === strpos( $menu_item[0], _x( 'Orders', 'Admin menu name', $this->plugin->get_plugin_name() ) ) ) {
						$submenu['woocommerce'][ $key ][0] .= ' <span class="awaiting-mod update-plugins count-' . $order_count . '"><span class="processing-count">' . number_format_i18n( $order_count ) . '</span></span>';
						break;
					}
				}
			}
		}
	}

	/**
	 * Reorder the WC menu items in admin.
	 *
	 * @param mixed $menu_order
	 * @return array
	 */
	public function menu_order( $menu_order ) {
		// Initialize our custom order array
		$woocommerce_menu_order = [];

		// Get the index of our custom separator
		$woocommerce_separator = array_search( 'separator-woocommerce', $menu_order );

		// Get index of book menu
		$woocommerce_product = array_search( 'edit.php?post_type=book', $menu_order );

		// Loop through menu order and do some rearranging
		foreach ( $menu_order as $index => $item ) {

			if ( ( ( 'woocommerce' ) == $item ) ) {
				$woocommerce_menu_order[] = 'separator-woocommerce';
				$woocommerce_menu_order[] = $item;
				$woocommerce_menu_order[] = 'edit.php?post_type=book';
				unset( $menu_order[ $woocommerce_separator ] );
				unset( $menu_order[ $woocommerce_product ] );
			} elseif ( ! in_array( $item, [ 'separator-woocommerce' ] ) ) {
				$woocommerce_menu_order[] = $item;
			}
		}

		// Return order
		return $woocommerce_menu_order;
	}

	/**
	 * Custom menu order.
	 *
	 * @return bool
	 */
	public function custom_menu_order() {
		return current_user_can( 'manage_woocommerce' );
	}

	/**
	 * Init the reports page.
	 */
	public function reports_page() {
		WC_Admin_Reports::output();
	}

	/**
	 * Init the settings page.
	 */
	public function settings_page() {
		return $this->plugin->admin->settings->output();
		//WC_Admin_Settings::output();
		// \Dazzle\Fusion\Admin\Settings
	}

	/**
	 * Init the attributes page.
	 */
	public function attributes_page() {
		WC_Admin_Attributes::output();
	}

	/**
	 * Init the status page.
	 */
	public function status_page() {
		WC_Admin_Status::output();
	}

	/**
	 * Add the "Visit Store" link in admin bar main menu.
	 *
	 * @since 2.4.0
	 * @param WP_Admin_Bar $wp_admin_bar
	 */
	public function admin_bar_menus( $wp_admin_bar ) {
		if ( ! is_admin() || ! is_user_logged_in() ) {
			return;
		}

		// Show only when the user is a member of this site, or they're a super admin.
		if ( ! is_user_member_of_blog() && ! is_super_admin() ) {
			return;
		}

		// Don't display when shop page is the same of the page on front.
		foreach ($this->plugin->config->get_post_type() as $posts => $post)
		{
			if ( get_option( 'page_on_front' ) == $this->plugin->core->get_pages()->get_page_id( $post['post_type'] ) )
			{
				return;
			}

			// Add an option to visit the store.
			$wp_admin_bar->add_node( [
				'parent' => 'site-name',
				'id'     => 'view-store',
				'title'  => __( 'Visit Store', $this->plugin->get_plugin_name() ),
				'href'   => $this->plugin->core->get_pages()->get_page_permalink( $post['post_type'] ),
			] );
		}
	}
}