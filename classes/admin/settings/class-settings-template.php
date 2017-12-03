<?php
/**
 * Plugin Settings Template.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Settings
 * @subpackage Template
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Plugin Settings Template.
 *
 * This class defines all code necessary to run during the plugin's settings administration template.
 *
 * @since      1.0.0
 * @package    Settings
 * @subpackage Template
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class Fusion_Settings_Template
{
	/**
	 * The single instance of the class.
	 *
	 * @var WooCommerce
	 * @since 2.1
	 */
	protected static $instance = null;

	/**
	 * Settings Template Instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Fusion_Settings_Template $template Settings Template Instance..
	 */
	public $template = NULL;

	public $extension = NULL;

	public $directory = NULL;

	//public $file = NULL;

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

	public function get_template()
	{
		//return include( $this->plugin->include_admin( 'views' ) . $this->template . '' );
		// views/html
		// views/admin/settings
		// html-admin-settings.php
		return $this->template;
	}

	public function set_template( $directory, $template, $extension = 'php' )
	{
		$this->directory = $directory;
		$this->template = $template;
		$this->extension = $extension;
	}

	public function display()
	{
		//
	}
}
