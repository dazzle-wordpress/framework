<?php
/**
 * General Settings for Settings Page.
 *
 * @link       https://dazzlesoftware.org
 * @since      1.0.0
 *
 * @package    Admin\Settings
 * @subpackage Admin\Settings\Page
 */

namespace Dazzle\Fusion\Admin\Settings;

use Dazzle\Fusion\Plugin;
use Dazzle\Fusion\Admin\Settings\Page;

/**
 * General Settings for Settings Page.
 *
 * @package    Admin\Settings
 * @subpackage Admin\Settings\General
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class General extends Page
{
	/**
	 * Constructor.
	 */
	public function __construct( Plugin $plugin )
	{
		$this->plugin = $plugin;
		$this->id    = 'general';
		$this->label = __( 'General', $this->plugin->get_plugin_name() );

		parent::__construct( $plugin );
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {

		$currency_code_options = get_woocommerce_currencies();

		foreach ( $currency_code_options as $code => $name ) {
			$currency_code_options[ $code ] = $name . ' (' . get_woocommerce_currency_symbol( $code ) . ')';
		}

		$settings = apply_filters( $this->plugin->get_plugin_prefix() . '_general_settings', array(

			array(
				'title'    => __( 'Store Address', $this->plugin->get_plugin_name() ),
				'type'     => 'title',
				'desc'     => __( 'This is where your business is located. Tax rates and shipping rates will use this address.', $this->plugin->get_plugin_name() ),
				'id'       => 'store_address',
			),

			array(
				'title'    => __( 'Address line 1', $this->plugin->get_plugin_name() ),
				'desc'     => __( 'The street address for your business location.', $this->plugin->get_plugin_name() ),
				'id'       => $this->plugin->get_plugin_prefix() . '_store_address',
				'default'  => '',
				'type'     => 'text',
				'desc_tip' => true,
			),

			array(
				'title'    => __( 'Address line 2', $this->plugin->get_plugin_name() ),
				'desc'     => __( 'An additional, optional address line for your business location.', $this->plugin->get_plugin_name() ),
				'id'       => $this->plugin->get_plugin_prefix() . '_store_address_2',
				'default'  => '',
				'type'     => 'text',
				'desc_tip' => true,
			),

			array(
				'title'    => __( 'City', $this->plugin->get_plugin_name() ),
				'desc'     => __( 'The city in which your business is located.', $this->plugin->get_plugin_name() ),
				'id'       => $this->plugin->get_plugin_prefix() . '_store_city',
				'default'  => '',
				'type'     => 'text',
				'desc_tip' => true,
			),

			array(
				'title'    => __( 'Country / State', $this->plugin->get_plugin_name() ),
				'desc'     => __( 'The country and state or province, if any, in which your business is located.', $this->plugin->get_plugin_name() ),
				'id'       => $this->plugin->get_plugin_prefix() . '_default_country',
				'default'  => 'GB',
				'type'     => 'single_select_country',
				'desc_tip' => true,
			),

			array(
				'title'    => __( 'Postcode / ZIP', $this->plugin->get_plugin_name() ),
				'desc'     => __( 'The postal code, if any, in which your business is located.', $this->plugin->get_plugin_name() ),
				'id'       => $this->plugin->get_plugin_prefix() . '_store_postcode',
				'css'      => 'min-width:50px;',
				'default'  => '',
				'type'     => 'text',
				'desc_tip' => true,
			),

			array( 'type' => 'sectionend', 'id' => 'store_address' ),

			array( 'title' => __( 'General options', $this->plugin->get_plugin_name() ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

			array(
				'title'    => __( 'Selling location(s)', $this->plugin->get_plugin_name() ),
				'desc'     => __( 'This option lets you limit which countries you are willing to sell to.', $this->plugin->get_plugin_name() ),
				'id'       => $this->plugin->get_plugin_prefix() . '_allowed_countries',
				'default'  => 'all',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'all'        => __( 'Sell to all countries', $this->plugin->get_plugin_name() ),
					'all_except' => __( 'Sell to all countries, except for&hellip;', $this->plugin->get_plugin_name() ),
					'specific'   => __( 'Sell to specific countries', $this->plugin->get_plugin_name() ),
				),
			),

			array(
				'title'   => __( 'Sell to all countries, except for&hellip;', $this->plugin->get_plugin_name() ),
				'desc'    => '',
				'id'      => $this->plugin->get_plugin_prefix() . '_all_except_countries',
				'css'     => 'min-width: 350px;',
				'default' => '',
				'type'    => 'multi_select_countries',
			),

			array(
				'title'   => __( 'Sell to specific countries', $this->plugin->get_plugin_name() ),
				'desc'    => '',
				'id'      => $this->plugin->get_plugin_prefix() . '_specific_allowed_countries',
				'css'     => 'min-width: 350px;',
				'default' => '',
				'type'    => 'multi_select_countries',
			),

			array(
				'title'    => __( 'Shipping location(s)', $this->plugin->get_plugin_name() ),
				'desc'     => __( 'Choose which countries you want to ship to, or choose to ship to all locations you sell to.', $this->plugin->get_plugin_name() ),
				'id'       => $this->plugin->get_plugin_prefix() . '_ship_to_countries',
				'default'  => '',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'desc_tip' => true,
				'options'  => array(
					''         => __( 'Ship to all countries you sell to', $this->plugin->get_plugin_name() ),
					'all'      => __( 'Ship to all countries', $this->plugin->get_plugin_name() ),
					'specific' => __( 'Ship to specific countries only', $this->plugin->get_plugin_name() ),
					'disabled' => __( 'Disable shipping &amp; shipping calculations', $this->plugin->get_plugin_name() ),
				),
			),

			array(
				'title'   => __( 'Ship to specific countries', $this->plugin->get_plugin_name() ),
				'desc'    => '',
				'id'      => $this->plugin->get_plugin_prefix() . '_specific_ship_to_countries',
				'css'     => '',
				'default' => '',
				'type'    => 'multi_select_countries',
			),

			array(
				'title'    => __( 'Default customer location', $this->plugin->get_plugin_name() ),
				'id'       => $this->plugin->get_plugin_prefix() . '_default_customer_address',
				'desc_tip' => __( 'This option determines a customers default location. The MaxMind GeoLite Database will be periodically downloaded to your wp-content directory if using geolocation.', $this->plugin->get_plugin_name() ),
				'default'  => 'geolocation',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'options'  => array(
					''                 => __( 'No location by default', $this->plugin->get_plugin_name() ),
					'base'             => __( 'Shop base address', $this->plugin->get_plugin_name() ),
					'geolocation'      => __( 'Geolocate', $this->plugin->get_plugin_name() ),
					'geolocation_ajax' => __( 'Geolocate (with page caching support)', $this->plugin->get_plugin_name() ),
				),
			),

			array(
				'title'   => __( 'Enable taxes', $this->plugin->get_plugin_name() ),
				'desc'    => __( 'Enable taxes and tax calculations', $this->plugin->get_plugin_name() ),
				'id'      => $this->plugin->get_plugin_prefix() . '_calc_taxes',
				'default' => 'no',
				'type'    => 'checkbox',
			),

			array(
				'title'   => __( 'Store notice', $this->plugin->get_plugin_name() ),
				'desc'    => __( 'Enable site-wide store notice text', $this->plugin->get_plugin_name() ),
				'id'      => $this->plugin->get_plugin_prefix() . '_demo_store',
				'default' => 'no',
				'type'    => 'checkbox',
			),

			array(
				'title'    => __( 'Store notice text', $this->plugin->get_plugin_name() ),
				'desc'     => '',
				'id'       => $this->plugin->get_plugin_prefix() . '_demo_store_notice',
				'default'  => __( 'This is a demo store for testing purposes &mdash; no orders shall be fulfilled.', $this->plugin->get_plugin_name() ),
				'type'     => 'textarea',
				'css'     => 'width:350px; height: 65px;',
				'autoload' => false,
			),

			array( 'type' => 'sectionend', 'id' => 'general_options' ),

			array( 'title' => __( 'Currency options', $this->plugin->get_plugin_name() ), 'type' => 'title', 'desc' => __( 'The following options affect how prices are displayed on the frontend.', $this->plugin->get_plugin_name() ), 'id' => 'pricing_options' ),

			array(
				'title'    => __( 'Currency', $this->plugin->get_plugin_name() ),
				'desc'     => __( 'This controls what currency prices are listed at in the catalog and which currency gateways will take payments in.', $this->plugin->get_plugin_name() ),
				'id'       => $this->plugin->get_plugin_prefix() . '_currency',
				'default'  => 'GBP',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'desc_tip' => true,
				'options'  => $currency_code_options,
			),

			array(
				'title'    => __( 'Currency position', $this->plugin->get_plugin_name() ),
				'desc'     => __( 'This controls the position of the currency symbol.', $this->plugin->get_plugin_name() ),
				'id'       => $this->plugin->get_plugin_prefix() . '_currency_pos',
				'class'    => 'wc-enhanced-select',
				'default'  => 'left',
				'type'     => 'select',
				'options'  => array(
					'left'        => __( 'Left', $this->plugin->get_plugin_name() ) . ' (' . get_woocommerce_currency_symbol() . '99.99)',
					'right'       => __( 'Right', $this->plugin->get_plugin_name() ) . ' (99.99' . get_woocommerce_currency_symbol() . ')',
					'left_space'  => __( 'Left with space', $this->plugin->get_plugin_name() ) . ' (' . get_woocommerce_currency_symbol() . ' 99.99)',
					'right_space' => __( 'Right with space', $this->plugin->get_plugin_name() ) . ' (99.99 ' . get_woocommerce_currency_symbol() . ')',
				),
				'desc_tip' => true,
			),

			array(
				'title'    => __( 'Thousand separator', $this->plugin->get_plugin_name() ),
				'desc'     => __( 'This sets the thousand separator of displayed prices.', $this->plugin->get_plugin_name() ),
				'id'       => $this->plugin->get_plugin_prefix() . '_price_thousand_sep',
				'css'      => 'width:50px;',
				'default'  => ',',
				'type'     => 'text',
				'desc_tip' => true,
			),

			array(
				'title'    => __( 'Decimal separator', $this->plugin->get_plugin_name() ),
				'desc'     => __( 'This sets the decimal separator of displayed prices.', $this->plugin->get_plugin_name() ),
				'id'       => $this->plugin->get_plugin_prefix() . '_price_decimal_sep',
				'css'      => 'width:50px;',
				'default'  => '.',
				'type'     => 'text',
				'desc_tip' => true,
			),

			array(
				'title'    => __( 'Number of decimals', $this->plugin->get_plugin_name() ),
				'desc'     => __( 'This sets the number of decimal points shown in displayed prices.', $this->plugin->get_plugin_name() ),
				'id'       => $this->plugin->get_plugin_prefix() . '_price_num_decimals',
				'css'      => 'width:50px;',
				'default'  => '2',
				'desc_tip' => true,
				'type'     => 'number',
				'custom_attributes' => array(
					'min'  => 0,
					'step' => 1,
				),
			),

			array( 'type' => 'sectionend', 'id' => 'pricing_options' ),

		) );

		return apply_filters( $this->plugin->get_plugin_prefix() . '_get_settings_' . $this->id, $settings );
	}

	/**
	 * Output a color picker input box.
	 *
	 * @param mixed $name
	 * @param string $id
	 * @param mixed $value
	 * @param string $desc (default: '')
	 */
	public function color_picker( $name, $id, $value, $desc = '' ) {
		echo '<div class="color_box">' . wc_help_tip( $desc ) . '
			<input name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
		</div>';
	}

	/**
	 * Save settings.
	 */
	public function save() {
		$settings = $this->get_settings();

		WC_Admin_Settings::save_fields( $settings );
	}
}

// return new Fusion_Settings_General(); // @todo move this to class to init settings
