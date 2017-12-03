<?php
/**
 * Settings Page/Tab for Settings.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Admin\Settings
 * @subpackage Admin\Settings\Page
 */

namespace Dazzle\Fusion\Admin\Settings;

use Dazzle\Fusion\Plugin;

/**
 * Settings Page/Tab for Settings.
 *
 * @package    Admin\Settings
 * @subpackage Admin\Settings\Page
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
abstract class Page
{
	/**
	 * Setting page id.
	 *
	 * @var string
	 */
	protected $settings = NULL;

	/**
	 * Setting page id.
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * Setting page label.
	 *
	 * @var string
	 */
	protected $label = '';

	/**
	 * Constructor.
	 */
	public function __construct( Plugin $plugin )
	{
		$this->plugin = $plugin;
		echo "Hello Settings Page";
		add_filter( $this->plugin->get_plugin_prefix() . '_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( $this->plugin->get_plugin_prefix() . '_sections_' . $this->id, array( $this, 'output_sections' ) );
		add_action( $this->plugin->get_plugin_prefix() . '_settings_' . $this->id, array( $this, 'output' ) );
		add_action( $this->plugin->get_plugin_prefix() . '_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings page ID.
	 * @since 3.0.0
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get settings page label.
	 * @since 3.0.0
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * Add this page to settings.
	 *
	 * @param array $pages
	 *
	 * @return mixed
	 */
	public function add_settings_page( $pages ) {
		$pages[ $this->id ] = $this->label;

		return $pages;
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {
		return apply_filters( $this->plugin->get_plugin_prefix() . '_get_settings_' . $this->id, array() );
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		return apply_filters( $this->plugin->get_plugin_prefix() . '_get_sections_' . $this->id, array() );
	}

	/**
	 * Output sections.
	 */
	public function output_sections() {
		global $current_section;

		$sections = $this->get_sections();

		$this->settings = $this->plugin->config->get_settings();

		if ( empty( $sections ) || 1 === sizeof( $sections ) ) {
			return;
		}

		echo '<ul class="subsubsub">';

		$array_keys = array_keys( $sections );

		foreach ( $sections as $id => $label )
		{
			// @todo might need change slug to this. must be tested!
			//$this->settings['parent_slug']
			echo '<li><a href="' . admin_url( 'admin.php?page=' . $this->settings['menu_slug'] . '&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
		}

		echo '</ul><br class="clear" />';
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		$settings = $this->get_settings();
		$this->plugin->admin->settings->output_fields( $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings();
		//WC_Admin_Settings::save_fields( $settings );
		$this->plugin->admin->settings->save_fields( $settings );

		if ( $current_section ) {
			do_action( $this->plugin->get_plugin_prefix() . '_update_options_' . $this->id . '_' . $current_section );
		}
	}
}
