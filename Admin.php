<?php
/**
 * Plugin Administration.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Classes
 * @subpackage Admin
 */

namespace Dazzle\Fusion;

use Dazzle\Fusion\Admin\Install as Installer;
use Dazzle\Fusion\Admin\Uninstall as Uninstaller;
use Dazzle\Fusion\Admin\Menus as Navigation;
use Dazzle\Fusion\Admin\Settings as Options;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's administration.
 *
 * @since      1.0.0
 * @package    Classes
 * @subpackage Admin
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class Admin
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
	 * The unique identifier of this plugin installer.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $install    The string used to uniquely identify this plugin install.
	 */
	public $install;

	/**
	 * The unique identifier of this plugin uninstaller.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $uninstall    The string used to uniquely identify this plugin uninstaller.
	 */
	public $uninstall;

	/**
	 * The unique identifier of this plugin menus.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $menus    The string used to uniquely identify this plugin menus.
	 */
	public $menus;

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
		$this->includes();
		$this->init_classes();
		$this->install();
		$this->uninstall();
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in PluginName_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PluginName_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		\wp_enqueue_style(
			$this->plugin->get_plugin_name(),
			$this->plugin->plugin_dir_url() . 'dist/styles/plugin-name-admin.css',
			array(),
			$this->plugin->get_version(),
			'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in PluginName_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PluginName_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		\wp_enqueue_script(
			$this->plugin->get_plugin_name(),
			$this->plugin->plugin_dir_url() . 'dist/scripts/plugin-name-admin.js',
			array( 'jquery' ),
			$this->plugin->get_version(),
			false );

	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes()
	{
		/**
		 * Core classes.
		 */
		//include_once( $this->plugin->include_classes( 'admin' ) . 'class-admin-install.php' );
		//include_once( $this->plugin->include_classes( 'admin' ) . 'class-admin-uninstall.php' );
		//include_once( $this->plugin->include_classes( 'admin' ) . 'class-admin-menus.php' );
		//include_once( $this->plugin->include_classes( 'admin' ) . 'class-admin-settings.php' );
		/**
		 * Core includes.
		 */
		//include_once( $this->plugin->include( 'includes' ) . 'admin-functions.php' );
	}

	public function init_classes()
	{
		$this->install = new Installer( $this->plugin );
		$this->uninstall = new Uninstaller( $this->plugin );
		$this->menus = new Navigation( $this->plugin );
		$this->settings = new Options( $this->plugin );
	}

	public function install()
	{
		add_action('activate_' . $this->plugin->get_plugin_basename(), [ $this->install, 'activate' ] );
	}

	public function uninstall()
	{
		add_action('deactivate_' . $this->plugin->get_plugin_basename(), [ $this->uninstall, 'deactivate' ] );
	}
}
