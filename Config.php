<?php

/**
 * The config configuration of the plugin.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Classes
 * @subpackage Config
 */

namespace Dazzle\Fusion;

/**
 * The config configuration of the plugin.
 *
 * Defines the plugin name, Posts, Metabox, Taxonomies and Shortcodes.
 *
 * @package    Classes
 * @subpackage Config
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class Config
{
	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin $plugin This plugin's instance.
	 */
	public $plugin;

	private $settings = [
		/* The slug name for the parent menu (or the file name of a standard WordPress admin page). */
		'parent_slug'  => 'edit.php?post_type=book',
		/* The text to be displayed in the title tags of the page when the menu is selected. */
		'page_title' => 'WooCommerce Settings',
		/* The text to be used for the menu. */
		'menu_title' => 'Settings',
		/* The capability required for this menu to be displayed to the user. */
		'capability' => 'manage_options',
		/* The slug name to refer to this menu by. Should be unique for this menu page and only include lowercase alphanumeric, dashes, and underscores characters to be compatible with sanitize_key(). */
		'menu_slug'  => 'wc-settings',
		/* The function to be called to output the content for this page. */
		'callable'   => 'settings_page',
		/* The URL to the icon to be used for this menu. */
		'icon_url'   => '',
		/* The position in the menu order this one should appear. */
		'position'   => 55.5,
		//'settings' => 'wc-settings', //@todo move this above once we corrected the implementation
	];
	/**
	 * The unique capabilities assigned to post types of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $capabilities    The array used to uniquely identify taxonomy followed by the post type for the taxonomy.
	 */
	private $capabilities = [
		'products' => [
			'capabilities'          =>
				[
					'manage_terms' => 'manage_product_terms',
					'edit_terms'   => 'edit_product_terms',
					'delete_terms' => 'delete_product_terms',
					'assign_terms' => 'assign_product_terms',
			],
		]
	];

	private $post_type = [
		'products' => [
			'singular_name' => 'product',
			'plural_name'   => 'products',
		    'post_type' => 'book',
			'args' => [
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => [ 
					'slug'       => 'product',
					'with_front' => true,
					'pages'      => true
				],
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ]
			],
		],
	];

	/**
	 * The unique taxonomies assigned to post types of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $taxonomies    The array used to uniquely identify taxonomy followed by the post type for the taxonomy.
	 */
	private $taxonomies = [
		'products' => [
			'singular_name' => 'category',
			'plural_name'   => 'categories',
		    'post_type' => 'book',
			'taxonomy'  => 'product_categories',
			'args' => [
				'hierarchical'          => true,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'rewrite'               => 
					[
						'slug' => 'product-categories',
						'with_front'   => false,
						'hierarchical' => true
					],
				//'capabilities'          =>
				//	[
				//		'manage_terms' => 'manage_product_terms',
				//		'edit_terms'   => 'edit_product_terms',
				//		'delete_terms' => 'delete_product_terms',
				//		'assign_terms' => 'assign_product_terms',
				//	],
			],
		],
	];

	private $metabox = [
		'products' => [
		
		]
	];

	/**
	 * The unique shortcodes assigned to post types of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $shortcodes    The array used to uniquely identify shortcodes followed by the post type or core for the shortcodes.
	 */
	private $shortcodes = [
		'products' => [
			'product'                    => 'product',
			'product_page'               => 'product_page',
			'product_category'           => 'product_category',
			'product_categories'         => 'product_categories',
			'products'                   => 'products',
			'recent_products'            => 'recent_products',
			'top_rated_products'         => 'top_rated_products',
			'featured_products'          => 'featured_products',
			'related_products'           => 'related_products',
		],
		'global' => [
			'woocommerce_my_account'     => 'my_account',
		]
	];

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * 
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
		$this->includes();
		$this->init_classes();
		$this->init_hooks();
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes()
	{
		/**
		 * Config Classes.
		 */
	}

	public function init_classes()
	{
		//
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 2.3
	 */
	private function init_hooks()
	{
		//
	}

	/**
	 * The name of the plugin capabilities used to uniquely identify it within the context of
	 * WordPress and to define hooks functionality.
	 *
	 * @since     1.0.0
	 * @return    array    The name of the mapped capabilities.
	 */
	public function get_settings()
	{
		return $this->settings;
	}

	/**
	 * The name of the plugin capabilities used to uniquely identify it within the context of
	 * WordPress and to define hooks functionality.
	 *
	 * @since     1.0.0
	 * @return    array    The name of the mapped capabilities.
	 */
	public function get_capabilities()
	{
		return $this->capabilities;
	}

	/**
	 * The name of the plugin post_type used to uniquely identify it within the context of
	 * WordPress and to define hooks functionality.
	 *
	 * @since     1.0.0
	 * @return    array    The name of the mapped post_type.
	 */
	public function get_post_type()
	{
		return $this->post_type;
	}

	/**
	 * The name of the plugin taxonomies used to uniquely identify it within the context of
	 * WordPress and to define hooks functionality.
	 *
	 * @since     1.0.0
	 * @return    array    The name of the mapped taxonomies.
	 */
	public function get_taxonomies()
	{
		return $this->taxonomies;
	}

	/**
	 * The name of the plugin metabox used to uniquely identify it within the context of
	 * WordPress and to define hooks functionality.
	 *
	 * @since     1.0.0
	 * @return    array    The name of the mapped metabox.
	 */
	public function get_metabox()
	{
		return $this->metabox;
	}

	/**
	 * The name of the plugin shortcodes used to uniquely identify it within the context of
	 * WordPress and to define hooks functionality.
	 *
	 * @since     1.0.0
	 * @return    array    The name of the mapped shortcodes.
	 */
	public function get_shortcodes()
	{
		return $this->shortcodes;
	}
}
