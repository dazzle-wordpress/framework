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
class Posts
{
	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin $plugin This plugin's instance.
	 */
	public $plugin;

	private $labels;

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

	public function add_post_type()
	{
		foreach ($this->plugin->config->get_post_type() as $posts => $post)
		{
			$this->labels = [
				'labels' => [
					'name'                  => __( ucfirst($post['plural_name']), 'your-plugin-textdomain' ),
					'singular_name'         => __( ucfirst($post['singular_name']), 'your-plugin-textdomain' ),
					'menu_name'             => __( ucfirst($post['plural_name']), 'your-plugin-textdomain' ),
					'name_admin_bar'        => __( ucfirst($post['singular_name']), 'your-plugin-textdomain' ),
					'add_new'               => __( 'Add New', 'your-plugin-textdomain' ),
					'add_new_item'          => __( 'Add New ' . ucfirst($post['singular_name']), 'your-plugin-textdomain' ),
					'new_item'              => __( 'New ' . ucfirst($post['singular_name']), 'your-plugin-textdomain' ),
					'edit_item'             => __( 'Edit ' . ucfirst($post['singular_name']), 'your-plugin-textdomain' ),
					'view_item'             => __( 'View ' . ucfirst($post['singular_name']), 'your-plugin-textdomain' ),
					'view_items'            => __( 'View ' . ucfirst($post['plural_name']), 'your-plugin-textdomain' ),
					'all_items'             => __( 'All ' . ucfirst($post['plural_name']), 'your-plugin-textdomain' ),
					'archives'              => __( 'Archives ' . ucfirst($post['plural_name']), 'your-plugin-textdomain' ),
					'attributes'            => __( ucfirst($post['singular_name']) . ' Attributes', 'your-plugin-textdomain' ),
					'insert_into_item'      => __( 'Insert Into ' . ucfirst($post['singular_name']), 'your-plugin-textdomain' ),
					'uploaded_to_this_item' => __( 'Uploaded To This ' . ucfirst($post['singular_name']), 'your-plugin-textdomain' ),
					'featured_image'        => __( 'Featured Image', 'your-plugin-textdomain' ),
					'set_featured_image'    => __( 'Set Featured Image', 'your-plugin-textdomain' ),
					'remove_featured_image' => __( 'Remove Featured Image', 'your-plugin-textdomain' ),
					'use_featured_image'    => __( 'Use As Featured Image', 'your-plugin-textdomain' ),
					'search_items'          => __( 'Search ' . ucfirst($post['plural_name']), 'your-plugin-textdomain' ),
					'parent_item_colon'     => __( 'Parent ' . ucfirst($post['plural_name']) . ':', 'your-plugin-textdomain' ),
					'not_found'             => __( 'No ' . ucfirst($post['plural_name']) . ' Found.', 'your-plugin-textdomain' ),
					'not_found_in_trash'    => __( 'No ' . ucfirst($post['plural_name']) . ' Found In Trash.', 'your-plugin-textdomain' )
				]
			];
			register_post_type( $post['post_type'],  array_merge($post['args'], $this->labels) );
		}
	}
}
