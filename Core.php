<?php

/**
 * The core functionality of the plugin.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Classes
 * @subpackage Core
 */

namespace Dazzle\Fusion;

/**
 * The core functionality of the plugin.
 *
 * Defines the plugin core specific functionality of the plugin.
 *
 * @package    Classes
 * @subpackage Core
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class Core
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
	 * The unique identifier of this plugin conditional.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $conditional    The string used to uniquely identify this plugin conditional.
	 */
	public $conditional;

	/**
	 * The posts that's responsible for maintaining and registering all hooks that powers
	 * the plugin pages.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Fusion_Pages    $pages    Maintains and registers all pages for the plugin.
	 */
	protected $pages;

	/**
	 * The unique identifier of this plugin media.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $media    The string used to uniquely identify this plugin media.
	 */
	public $media;

	/**
	 * The unique identifier of this plugin language.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $language    The string used to uniquely identify this plugin language.
	 */
	public $language;

	/**
	 * The capabilities that's responsible for maintaining and registering all hooks that powers
	 * the plugin capabilities.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Fusion_Capabilities    $capabilities    Maintains and registers all capabilities for the plugin.
	 */
	protected $capabilities;

	/**
	 * The posts that's responsible for maintaining and registering all hooks that powers
	 * the plugin post types.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Fusion_Posts    $posts    Maintains and registers all post types for the plugin.
	 */
	protected $posts;

	/**
	 * The taxonomies that's responsible for maintaining and registering all hooks that powers
	 * the plugin taxonomies.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Fusion_Taxonomy    $taxonomies    Maintains and registers all taxonomies for the plugin.
	 */
	protected $taxonomies;

	/**
	 * The unique identifier of this plugin metabox.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $metabox    The string used to uniquely identify this plugin metabox.
	 */
	public $metabox;

	/**
	 * The shortcode that's responsible for maintaining and registering all hooks that powers
	 * the plugin shortcodes.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Fusion_Shortcode    $shortcode    Maintains and registers all shortcode for the plugin.
	 */
	protected $shortcode;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * 
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
		$this->init_classes();
		$this->init_hooks();
	}

	public function init_classes()
	{
		$this->capabilities = new \Dazzle\Fusion\Core\Capabilities( $this->plugin );
		$this->media = new \Dazzle\Fusion\Core\Media( $this->plugin );
		$this->metabox = new \Dazzle\Fusion\Core\Metabox( $this->plugin );
		$this->shortcode = new \Dazzle\Fusion\Core\Shortcode( $this->plugin );
		$this->conditional = new \Dazzle\Fusion\Core\Conditional( $this->plugin );
		$this->pages = new \Dazzle\Fusion\Core\Pages( $this->plugin );
		$this->posts = new \Dazzle\Fusion\Core\Posts( $this->plugin );
		$this->taxonomies = new \Dazzle\Fusion\Core\Taxonomies( $this->plugin );
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 2.3
	 */
	private function init_hooks()
	{
		add_action( 'init', [ $this->posts, 'add_post_type' ] );
		add_action( 'init', [ $this->taxonomies, 'add_taxonomies' ] );

		add_action( 'init', [ $this->shortcode, 'add_post_shortcodes' ] );
		add_action( $this->plugin->get_plugin_prefix() . '_after_init_hooks', [ $this, 'set_locale' ] );
	}

	/**
	 * The name of the plugin pages used to uniquely identify it within the context of
	 * WordPress and to define hooks functionality.
	 *
	 * @since     1.0.0
	 * @return    array    The name of the mapped pages.
	 */
	public function get_pages()
	{
		return $this->pages;
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function set_locale() {

		$this->language = new \Dazzle\Fusion\Core\Language( $this->plugin );
		$this->language->set_domain( $this->plugin->get_plugin_name() );
		$this->language->load_plugin_textdomain();

	}
}
