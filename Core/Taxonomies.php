<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Dazzle\Fusion
 * @subpackage Dazzle\Fusion\Frontend
 */

namespace Dazzle\Fusion\Core;

use Dazzle\Fusion\Plugin;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Fusion\Products
 * @subpackage Fusion\Products\Frontend
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class Taxonomies
{
	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Fusion $plugin This plugin's instance.
	 */
	public $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * 
	 * @param Fusion $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	public function add_taxonomies()
	{
		foreach ($this->plugin->config->get_taxonomies() as $taxonomies => $taxonomy)
		{
			$labels = [
				'labels' => [
					'name'                       => __( ucfirst($taxonomy['plural_name']), $this->plugin->get_plugin_name() ),
					'singular_name'              => __( ucfirst($taxonomy['singular_name']), $this->plugin->get_plugin_name() ),
					'search_items'               => __( 'Search ' . ucfirst($taxonomy['plural_name']), $this->plugin->get_plugin_name() ),
					'popular_items'              => __( 'Popular ' . ucfirst($taxonomy['plural_name']), $this->plugin->get_plugin_name() ),
					'all_items'                  => __( 'All ' . ucfirst($taxonomy['plural_name']), $this->plugin->get_plugin_name() ),
					'parent_item'                => __( 'Parent ' . ucfirst($taxonomy['singular_name']), $this->plugin->get_plugin_name() ),
					'parent_item_colon'          => __( 'Parent ' . ucfirst($taxonomy['singular_name']) . ':', $this->plugin->get_plugin_name() ),
					'edit_item'                  => __( 'Edit ' . ucfirst($taxonomy['singular_name']), $this->plugin->get_plugin_name() ),
					'update_item'                => __( 'Update '  . ucfirst($taxonomy['singular_name']), $this->plugin->get_plugin_name() ),
					'add_new_item'               => __( 'Add New ' . ucfirst($taxonomy['singular_name']), $this->plugin->get_plugin_name() ),
					'new_item_name'              => __( 'New ' . ucfirst($taxonomy['singular_name']) . ' Name', $this->plugin->get_plugin_name() ),
					'separate_items_with_commas' => __( 'Separate ' . ucfirst($taxonomy['plural_name']) . ' With Commas', $this->plugin->get_plugin_name() ),
					'add_or_remove_items'        => __( 'Add or remove ' . ucfirst($taxonomy['plural_name']), $this->plugin->get_plugin_name() ),
					'choose_from_most_used'      => __( 'Choose from the most used ' . ucfirst($taxonomy['plural_name']), $this->plugin->get_plugin_name() ),
					'not_found'                  => __( 'No ' . ucfirst($taxonomy['plural_name']) . ' Found.', $this->plugin->get_plugin_name() ),
					'menu_name'                  => __( ucfirst($taxonomy['singular_name']), $this->plugin->get_plugin_name() ),
				]
			];
			register_taxonomy( $taxonomy['taxonomy'], $taxonomy['post_type'], array_merge($taxonomy['args'], $labels) );
		}
	}
}
