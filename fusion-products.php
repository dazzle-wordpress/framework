<?php
/**
 * Plugin Name: Fusion Products
 * Plugin URI: https://www.fusion-glazing.co.uk
 * Description: An products page that helps you promote anything. Beautifully.
 * Version: 1.0
 * Author: Dazzle Software
 * Author URI: https://dazzlesoftware.org
 * Requires at least: 4.4
 * Tested up to: 4.9
 *
 * Text Domain: fusion
 * Domain Path: /languages/
 *
 * @package Dazzle\Fusion
 * @subpackage Dazzle\Fusion\Products
 * @author Dazzle Software
 */

namespace Dazzle\Fusion;

use Dazzle\Fusion\Frontend;
/**
 * Prevent the plugin from being accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Require the composer dependency of the plugin.
 */
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/*
 * Set error reporting to the level to which Fusion Framework code must comply.
 */
error_reporting(E_ALL | E_STRICT);

final class Plugin
{
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version = '1.0.0';

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $pluginname    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name = 'fusion-products';

	/**
	 * The unique prefix identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_prefix    The string used to uniquely identify this plugin.
	 */
	protected $plugin_prefix = 'fusion_products';

	/**
	 * The unique prefix identifier of this plugin templates.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $templates    The string used to uniquely identify this plugin templates.
	 */
	protected $templates = 'Templates';

	/**
	 * The unique identifier of this plugin file loader.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Object    $loader    The object used to uniquely identify this plugin file loader.
	 */
	protected $loader = NULL;

	/**
	 * The current template debug mode of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      bool    $template_debug    The current mode of the template debug.
	 */
	public $template_debug = true;

	/**
	 * The unique identifier of this plugin admin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $admin    The string used to uniquely identify this plugin admin.
	 */
	public $admin;

	/**
	 * The core functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $core    The string used to uniquely identify this plugin core.
	 */
	public $core;

	/**
	 * The config functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $config    The string used to uniquely identify this plugin config.
	 */
	public $config;

	/**
	 * The template that's responsible for maintaining and registering all hooks that powers
	 * the plugin templates.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Fusion_Template    $template    Maintains and registers all templates for the plugin.
	 */
	protected $template;

	/**
	 * The single instance of the class.
	 *
	 * @var WooCommerce
	 * @since 2.1
	 */
	protected static $instance = null;

	/**
	 * WooCommerce Constructor.
	 */
	public function __construct()
	{
		$this->includes();
		$this->init_classes();
		$this->init_hooks();
		echo "plugin_dir_url=" . $this->plugin_dir_url();
		do_action( $this->get_plugin_prefix() . '_loaded' );
	}

	/**
	 * Main Fusion Instance.
	 *
	 * Ensures only one instance of Fusion is loaded or can be loaded.
	 *
	 * @since 1.0
	 * @static
	 * @return FusionProducts - Main instance.
	 */
	public static function instance()
	{
		if ( is_null( self::$instance ) )
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{
		if ( $this->is_request( 'admin' ) )
		{
			$this->admin = new Admin( $this );
			$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_scripts' );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_frontend_hooks()
	{
		$plugin_frontend = new Frontend( $this );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_frontend, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_frontend, 'enqueue_scripts' );

	}

	/**
	 * Run Fusion Loader.
	 *
	 * Ensures only one hook instance of Fusion Loader is loaded before processing hooks.
	 *
	 * @since 1.0
	 * @static
	 * @return Fusion_Loader - Main Loader.
	 */
	public function run()
	{
		$this->define_admin_hooks();
		$this->define_frontend_hooks();
		$this->loader->run();
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes()
	{
		/**
		 * Class Loader.
		 */
		//include_once( $this->include( 'classes' ) . 'class-loader.php' );
		/**
		 * Core Classes.
		 */
		//include_once( $this->include_classes( 'core' ) . 'class-fusion-core.php' );
		//include_once( $this->include_classes( 'config' ) . 'class-fusion-config.php' );
		/**
		 * Admin classes.
		 */
		if ( $this->is_request( 'admin' ) ) {
			//include_once( $this->include( 'classes' ) . 'class-admin.php' );
		}

		/**
		 * Frontend classes.
		 */
		if ( $this->is_request( 'frontend' ) ) {
			$this->frontend_includes();
		}
	}

	public function init_classes()
	{
		// before init action.
		do_action( $this->get_plugin_prefix() . '_before_init_classes' );

		$this->loader = new \Dazzle\Fusion\Loader( $this );
		
		$this->core = new \Dazzle\Fusion\Core( $this );
		$this->config = new \Dazzle\Fusion\Config( $this );
		/**
		 * Frontend classes.
		 */
		if ( $this->is_request( 'frontend' ) ) {
			$this->template = new \Dazzle\Fusion\Template( $this );
		}

		/**
		 * Admin classes.
		 */
		//if ( $this->is_request( 'admin' ) )
		//{
		//	$this->admin = new \Dazzle\Fusion\Admin( $this );
		//}
		// init action.
		do_action( $this->get_plugin_prefix() . '_init_classes' );
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 2.3
	 */
	private function init_hooks()
	{
		// before init hooks action.
		do_action( $this->get_plugin_prefix() . '_before_init_hooks' );
		/*
		$this->loader->add_action( 'after_setup_theme', $this, 'setup_environment' );
		$this->loader->add_action( 'after_setup_theme', $this, 'include_template_functions', 11 );
		$this->loader->add_filter( 'template_include', $this->template, 'template_loader' );
		$this->loader->add_filter( 'comments_template', $this->template, 'comments_template_loader' );
		*/
		add_action( 'after_setup_theme', array( $this, 'setup_environment' ) );
		add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
		add_filter( 'template_include',  [ $this->template, 'template_loader' ] );
		add_filter( 'comments_template', [ $this->template, 'comments_template_loader' ] );
		// before init hooks action.
		do_action( $this->get_plugin_prefix() . '_after_init_hooks' );
	}

	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

	/**
	 * Check the active theme.
	 *
	 * @since  2.6.9
	 * @param  string $theme Theme slug to check.
	 * @return bool
	 */
	private function is_active_theme( $theme )
	{
		return get_template() === $theme;
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
		include_once( $this->include_classes( 'frontend' ) . 'class-template.php' );
	}

	/**
	 * Function used to Init Template Functions - This makes them pluggable by plugins and themes.
	 */
	public function include_template_functions()
	{
		include_once( $this->include('includes') . 'template-functions.php' );
	}

	public function include( $directory )
	{
		switch ($directory)
		{
			case 'classes':
				return trailingslashit( $this->get_plugin_path() . 'classes' );
				break;
			case 'includes':
				return trailingslashit( $this->get_plugin_path() . 'includes' );
				break;
			case 'languages':
				return trailingslashit( $this->get_plugin_path() . 'languages' );
				break;
			case 'templates':
				return trailingslashit( $this->get_plugin_path() . 'templates' );
				break;
			default:
				return trailingslashit( $this->get_plugin_path() . $directory );
		}
	}

	public function include_admin( $directory )
	{
		switch ($directory)
		{
			case 'settings':
				return trailingslashit( $this->include_classes( 'admin' ) . 'settings' );
				break;
			case 'metafield':
				return trailingslashit( $this->include_classes( 'admin' ) . 'metafield' );
				break;
			case 'views':
				return trailingslashit( $this->include_classes( 'admin' ) . 'views' );
				break;
			default:
				return trailingslashit( $this->include_classes( 'admin' ) . $directory );
		}
	}

	public function include_classes( $directory )
	{
		switch ($directory)
		{
			case 'admin':
				return trailingslashit( $this->include( 'classes' ) . 'admin' );
				break;
			case 'config':
				return trailingslashit( $this->include( 'classes' ) . 'config' );
				break;
			case 'core':
				return trailingslashit( $this->include( 'classes' ) . 'core' );
				break;
			case 'frontend':
				return trailingslashit( $this->include( 'classes' ) . 'frontend' );
				break;
			default:
				return trailingslashit( $this->include( 'classes' ) . $directory );
		}
	}

	/**
	 * Ensure theme and server variable compatibility and setup image sizes.
	 */
	public function setup_environment()
	{
		$this->add_thumbnail_support();
		$this->add_image_sizes();
	}

	/**
	 * Ensure post thumbnail support is turned on.
	 */
	private function add_thumbnail_support()
	{
		if ( ! current_theme_supports( 'post-thumbnails' ) )
		{
			add_theme_support( 'post-thumbnails' );
		}
		add_post_type_support( 'product', 'thumbnail' );
	}

	/**
	 * Add WC Image sizes to WP.
	 *
	 * @since 2.3
	 */
	private function add_image_sizes()
	{
		$shop_thumbnail = $this->core->media->get_image_size( 'shop_thumbnail' );
		$shop_catalog	= $this->core->media->get_image_size( 'shop_catalog' );
		$shop_single	= $this->core->media->get_image_size( 'shop_single' );

		add_image_size( 'shop_thumbnail', $shop_thumbnail['width'], $shop_thumbnail['height'], $shop_thumbnail['crop'] );
		add_image_size( 'shop_catalog', $shop_catalog['width'], $shop_catalog['height'], $shop_catalog['crop'] );
		add_image_size( 'shop_single', $shop_single['width'], $shop_single['height'], $shop_single['crop'] );
	}

	/**
	 * The name of the plugin prefix used to uniquely identify it within the context of
	 * WordPress and to define hooks functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_prefix()
	{
		return $this->plugin_prefix;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_dir_url() {
		return trailingslashit( plugin_dir_url(  __FILE__  ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function get_plugin_path()
	{
		return trailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * The name of the plugin basename used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_basename()
	{
		return untrailingslashit( plugin_basename( __FILE__ ) );
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public function template_path()
	{
		return apply_filters( $this->get_plugin_prefix() . '_template_path', $this->templates );
	}

	/**
	 * Get Ajax URL.
	 *
	 * @return string
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the template debug of the plugin.
	 *
	 * @since     1.0.0
	 * @return    bool    The template debug of the plugin.
	 */
	public function get_template_debug()
	{
		return $this->template_debug;
	}
}

/**
 * The code that runs during plugin activation.
 *
 * @since    1.0.0
 */
\register_activation_hook( __FILE__, '\Dazzle\Fusion\Admin\Install::init' );

/**
 * The code that runs during plugin deactivation.
 *
 * @since    1.0.0
 */
\register_deactivation_hook( __FILE__, '\Dazzle\Fusion\Admin\Uninstall::init' );

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
\add_action( 'plugins_loaded', function ()
{
    $plugin = new \Dazzle\Fusion\Plugin();
    $plugin->run();
} );