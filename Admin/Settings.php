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

use Dazzle\Fusion\Renderer\TwigRenderer as Template;

//use Dazzle\Fusion\Admin\Template\Settings as Options; //@todo move to its own template class to call all template classes

/* Settings */
use Dazzle\Fusion\Admin\Settings\General;

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
class Settings
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
	 * Setting pages.
	 *
	 * @var array
	 */
	private $settings = [];

	/**
	 * Error messages.
	 *
	 * @var array
	 */
	private static $errors   = [];

	/**
	 * Update messages.
	 *
	 * @var array
	 */
	private static $messages = [];

	/* settings classes instance */
	private $page = NULL;

	public $template = NULL;
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
		$this->init_classes();
		$this->init_hooks();
	}

	public function init_classes()
	{
		//$this->settings[] = new General( $this->plugin );
		//$this->template = new Options;
		$this->template = new Template;
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 2.3
	 */
	private function init_hooks()
	{
		$this->template->addFolder( $this->plugin->get_plugin_path() . $this->plugin->template_path() );
	}

	/**
	 * Include the settings page classes.
	 */
	public function get_settings_pages()
	{
		if ( empty( $this->settings ) )
		{
			$this->settings = [];

			include_once( $this->plugin->include_admin( 'settings' ) . 'class-settings-page.php' );

			$this->settings[] = include( $this->plugin->include_admin( 'settings' ) . 'class-settings-general.php' );
			//$this->settings[] = include( $this->plugin->include_admin( 'settings' ) . 'class-settings-products.php' );
			//$this->settings[] = include( $this->plugin->include_admin( 'settings' ) . 'class-settings-tax.php' );
			//$this->settings[] = include( $this->plugin->include_admin( 'settings' ) . 'class-settings-shipping.php' );
			//$this->settings[] = include( $this->plugin->include_admin( 'settings' ) . 'class-settings-checkout.php' );
			//$this->settings[] = include( $this->plugin->include_admin( 'settings' ) . 'class-settings-accounts.php' );
			//$this->settings[] = include( $this->plugin->include_admin( 'settings' ) . 'class-settings-emails.php' );
			//$this->settings[] = include( $this->plugin->include_admin( 'settings' ) . 'class-settings-integrations.php' );
			//$this->settings[] = include( $this->plugin->include_admin( 'settings' ) . 'class-settings-api.php' );

			$this->settings = apply_filters( $this->plugin->get_plugin_prefix() . '_get_settings_pages', $this->settings );
		}

		return $this->settings;
	}

	/**
	 * Save the settings.
	 */
	public static function save() {
		global $current_tab;

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'woocommerce-settings' ) ) {
			die( __( 'Action failed. Please refresh the page and retry.', $this->plugin->get_plugin_name() ) );
		}

		// Trigger actions
		do_action( $this->plugin->get_plugin_prefix() . '_settings_save_' . $current_tab );
		do_action( $this->plugin->get_plugin_prefix() . '_update_options_' . $current_tab );
		do_action( $this->plugin->get_plugin_prefix() . '_update_options' );

		self::add_message( __( 'Your settings have been saved.', $this->plugin->get_plugin_name() ) );
		self::check_download_folder_protection();

		// Clear any unwanted data and flush rules
		//delete_transient( $this->plugin->get_plugin_prefix() . '_cache_excluded_uris' );
		wp_schedule_single_event( time(), 'woocommerce_flush_rewrite_rules' );

		do_action( $this->plugin->get_plugin_prefix() . '_settings_saved' );
	}

	/**
	 * Add a message.
	 * @param string $text
	 */
	public static function add_message( $text ) {
		self::$messages[] = $text;
	}

	/**
	 * Add an error.
	 * @param string $text
	 */
	public static function add_error( $text ) {
		self::$errors[] = $text;
	}

	/**
	 * Output messages + errors.
	 */
	public static function show_messages() {
		if ( sizeof( self::$errors ) > 0 ) {
			foreach ( self::$errors as $error ) {
				echo '<div id="message" class="error inline"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
			}
		} elseif ( sizeof( self::$messages ) > 0 ) {
			foreach ( self::$messages as $message ) {
				echo '<div id="message" class="updated inline"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
			}
		}
	}

	public function test()
	{
		echo "Hello Buddy";
	}
	/**
	 * Settings page.
	 *
	 * Handles the display of the main woocommerce settings page in admin.
	 */
	public function output() {
		global $current_section, $current_tab;

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		do_action( $this->plugin->get_plugin_prefix() . '_settings_start' );

		wp_enqueue_script( $this->plugin->get_plugin_prefix() . '_settings', $this->plugin->plugin_dir_url() . 'assets/js/admin/settings' . $suffix . '.js', [ 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'iris', 'selectWoo' ], $this->plugin->get_version(), true );

		wp_localize_script( $this->plugin->get_plugin_prefix() . '_settings', 'settings_params', [ //woocommerce_settings_params
			'i18n_nav_warning' => __( 'The changes you made will be lost if you navigate away from this page.', $this->plugin->get_plugin_name() ),
		] );

		// Get tabs for the settings page
		$tabs = apply_filters( $this->plugin->get_plugin_prefix() . '_settings_tabs_array', [] );
		$tab_exists        = isset( $tabs[ $current_tab ] ) || has_action( 'woocommerce_sections_' . $current_tab ) || has_action( 'woocommerce_settings_' . $current_tab ) || has_action( 'woocommerce_settings_tabs_' . $current_tab );
		$current_tab_label = isset( $tabs[ $current_tab ] ) ? $tabs[ $current_tab ] : '';
		
		$settings = $this->plugin->config->get_settings();

		//$twig = new Twig_Environment($loader);
		//$this->template->addGlobal('text', $this->test());
		echo $this->template->render('Admin/Settings.twig', array(
			'plugin_path' => $this->plugin->get_plugin_path(),
			'tabs' => $tabs,
			'plugin_path' => $this->plugin->get_plugin_path(),
			
			'products' => array(
				'Tuxedo',
				'Bow tie',
				'Black Boxers',
			),
		));
		//$this->template->display();
	}

	/**
	 * Get a setting from the settings API.
	 *
	 * @param string $option_name
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public static function get_option( $option_name, $default = '' ) {
		// Array value
		if ( strstr( $option_name, '[' ) ) {

			parse_str( $option_name, $option_array );

			// Option name is first key
			$option_name = current( array_keys( $option_array ) );

			// Get value
			$option_values = get_option( $option_name, '' );

			$key = key( $option_array[ $option_name ] );

			if ( isset( $option_values[ $key ] ) ) {
				$option_value = $option_values[ $key ];
			} else {
				$option_value = null;
			}

		// Single value
		} else {
			$option_value = get_option( $option_name, null );
		}

		if ( is_array( $option_value ) ) {
			$option_value = array_map( 'stripslashes', $option_value );
		} elseif ( ! is_null( $option_value ) ) {
			$option_value = stripslashes( $option_value );
		}

		return ( null === $option_value ) ? $default : $option_value;
	}

	/**
	 * Output admin fields.
	 *
	 * Loops though the woocommerce options array and outputs each field.
	 *
	 * @param array[] $options Opens array to output
	 */
	public static function output_fields( $options ) {
		foreach ( $options as $value ) {
			if ( ! isset( $value['type'] ) ) {
				continue;
			}
			if ( ! isset( $value['id'] ) ) {
				$value['id'] = '';
			}
			if ( ! isset( $value['title'] ) ) {
				$value['title'] = isset( $value['name'] ) ? $value['name'] : '';
			}
			if ( ! isset( $value['class'] ) ) {
				$value['class'] = '';
			}
			if ( ! isset( $value['css'] ) ) {
				$value['css'] = '';
			}
			if ( ! isset( $value['default'] ) ) {
				$value['default'] = '';
			}
			if ( ! isset( $value['desc'] ) ) {
				$value['desc'] = '';
			}
			if ( ! isset( $value['desc_tip'] ) ) {
				$value['desc_tip'] = false;
			}
			if ( ! isset( $value['placeholder'] ) ) {
				$value['placeholder'] = '';
			}

			// Custom attribute handling
			$custom_attributes = [];

			if ( ! empty( $value['custom_attributes'] ) && is_array( $value['custom_attributes'] ) ) {
				foreach ( $value['custom_attributes'] as $attribute => $attribute_value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
				}
			}

			// Description handling
			$field_description = self::get_field_description( $value );
			extract( $field_description );

			// Switch based on type
			switch ( $value['type'] ) {

				// Section Titles
				case 'title':
					if ( ! empty( $value['title'] ) ) {
						echo '<h2>' . esc_html( $value['title'] ) . '</h2>';
					}
					if ( ! empty( $value['desc'] ) ) {
						echo wpautop( wptexturize( wp_kses_post( $value['desc'] ) ) );
					}
					echo '<table class="form-table">' . "\n\n";
					if ( ! empty( $value['id'] ) ) {
						do_action( 'woocommerce_settings_' . sanitize_title( $value['id'] ) );
					}
					break;

				// Section Ends
				case 'sectionend':
					if ( ! empty( $value['id'] ) ) {
						do_action( 'woocommerce_settings_' . sanitize_title( $value['id'] ) . '_end' );
					}
					echo '</table>';
					if ( ! empty( $value['id'] ) ) {
						do_action( 'woocommerce_settings_' . sanitize_title( $value['id'] ) . '_after' );
					}
					break;

				// Standard text inputs and subtypes like 'number'
				case 'text':
				case 'email':
				case 'number':
				case 'password' :
					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
							<input
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="<?php echo esc_attr( $value['type'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								value="<?php echo esc_attr( $option_value ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); ?>
								/> <?php echo $description; ?>
						</td>
					</tr><?php
					break;

				// Color picker.
				case 'color' :
					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">&lrm;
							<span class="colorpickpreview" style="background: <?php echo esc_attr( $option_value ); ?>"></span>
							<input
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="text"
								dir="ltr"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								value="<?php echo esc_attr( $option_value ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>colorpick"
								placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); ?>
								/>&lrm; <?php echo $description; ?>
								<div id="colorPickerDiv_<?php echo esc_attr( $value['id'] ); ?>" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>
						</td>
					</tr><?php
					break;

				// Textarea
				case 'textarea':

					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
							<?php echo $description; ?>

							<textarea
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); ?>
								><?php echo esc_textarea( $option_value );  ?></textarea>
						</td>
					</tr><?php
					break;

				// Select boxes
				case 'select' :
				case 'multiselect' :

					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
							<select
								name="<?php echo esc_attr( $value['id'] ); ?><?php echo ( 'multiselect' === $value['type'] ) ? '[]' : ''; ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); ?>
								<?php echo ( 'multiselect' == $value['type'] ) ? 'multiple="multiple"' : ''; ?>
								>
								<?php
									foreach ( $value['options'] as $key => $val ) {
										?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php

											if ( is_array( $option_value ) ) {
												selected( in_array( $key, $option_value ), true );
											} else {
												selected( $option_value, $key );
											}

										?>><?php echo $val ?></option>
										<?php
									}
								?>
							</select> <?php echo $description; ?>
						</td>
					</tr><?php
					break;

				// Radio inputs
				case 'radio' :

					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
							<fieldset>
								<?php echo $description; ?>
								<ul>
								<?php
									foreach ( $value['options'] as $key => $val ) {
										?>
										<li>
											<label><input
												name="<?php echo esc_attr( $value['id'] ); ?>"
												value="<?php echo $key; ?>"
												type="radio"
												style="<?php echo esc_attr( $value['css'] ); ?>"
												class="<?php echo esc_attr( $value['class'] ); ?>"
												<?php echo implode( ' ', $custom_attributes ); ?>
												<?php checked( $key, $option_value ); ?>
												/> <?php echo $val ?></label>
										</li>
										<?php
									}
								?>
								</ul>
							</fieldset>
						</td>
					</tr><?php
					break;

				// Checkbox input
				case 'checkbox' :

					$option_value    = self::get_option( $value['id'], $value['default'] );
					$visibility_class = [];

					if ( ! isset( $value['hide_if_checked'] ) ) {
						$value['hide_if_checked'] = false;
					}
					if ( ! isset( $value['show_if_checked'] ) ) {
						$value['show_if_checked'] = false;
					}
					if ( 'yes' == $value['hide_if_checked'] || 'yes' == $value['show_if_checked'] ) {
						$visibility_class[] = 'hidden_option';
					}
					if ( 'option' == $value['hide_if_checked'] ) {
						$visibility_class[] = 'hide_options_if_checked';
					}
					if ( 'option' == $value['show_if_checked'] ) {
						$visibility_class[] = 'show_options_if_checked';
					}

					if ( ! isset( $value['checkboxgroup'] ) || 'start' == $value['checkboxgroup'] ) {
						?>
							<tr valign="top" class="<?php echo esc_attr( implode( ' ', $visibility_class ) ); ?>">
								<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?></th>
								<td class="forminp forminp-checkbox">
									<fieldset>
						<?php
					} else {
						?>
							<fieldset class="<?php echo esc_attr( implode( ' ', $visibility_class ) ); ?>">
						<?php
					}

					if ( ! empty( $value['title'] ) ) {
						?>
							<legend class="screen-reader-text"><span><?php echo esc_html( $value['title'] ) ?></span></legend>
						<?php
					}

					?>
						<label for="<?php echo $value['id'] ?>">
							<input
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="checkbox"
								class="<?php echo esc_attr( isset( $value['class'] ) ? $value['class'] : '' ); ?>"
								value="1"
								<?php checked( $option_value, 'yes' ); ?>
								<?php echo implode( ' ', $custom_attributes ); ?>
							/> <?php echo $description ?>
						</label> <?php echo $tooltip_html; ?>
					<?php

					if ( ! isset( $value['checkboxgroup'] ) || 'end' == $value['checkboxgroup'] ) {
									?>
									</fieldset>
								</td>
							</tr>
						<?php
					} else {
						?>
							</fieldset>
						<?php
					}
					break;

				// Image width settings
				case 'image_width' :

					$image_size       = str_replace( '_image_size', '', $value['id'] );
					$size             = $this->plugin->core->media->get_image_size( $image_size );
					$width            = isset( $size['width'] ) ? $size['width'] : $value['default']['width'];
					$height           = isset( $size['height'] ) ? $size['height'] : $value['default']['height'];
					$crop             = isset( $size['crop'] ) ? $size['crop'] : $value['default']['crop'];
					$disabled_attr    = '';
					$disabled_message = '';

					if ( has_filter( $this->plugin->get_plugin_prefix() . '_get_image_size_' . $image_size ) ) {
						$disabled_attr = 'disabled="disabled"';
						$disabled_message = "<p><small>" . __( 'The settings of this image size have been disabled because its values are being overwritten by a filter.', $this->plugin->get_plugin_name() ) . "</small></p>";
					}

					?><tr valign="top">
						<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?> <?php echo $tooltip_html . $disabled_message; ?></th>
						<td class="forminp image_width_settings">

							<input name="<?php echo esc_attr( $value['id'] ); ?>[width]" <?php echo $disabled_attr; ?> id="<?php echo esc_attr( $value['id'] ); ?>-width" type="text" size="3" value="<?php echo $width; ?>" /> &times; <input name="<?php echo esc_attr( $value['id'] ); ?>[height]" <?php echo $disabled_attr; ?> id="<?php echo esc_attr( $value['id'] ); ?>-height" type="text" size="3" value="<?php echo $height; ?>" />px

							<label><input name="<?php echo esc_attr( $value['id'] ); ?>[crop]" <?php echo $disabled_attr; ?> id="<?php echo esc_attr( $value['id'] ); ?>-crop" type="checkbox" value="1" <?php checked( 1, $crop ); ?> /> <?php _e( 'Hard crop?', $this->plugin->get_plugin_name() ); ?></label>

							</td>
					</tr><?php
					break;

				// Single page selects
				case 'single_select_page' :

					$args = [
						'name'             => $value['id'],
						'id'               => $value['id'],
						'sort_column'      => 'menu_order',
						'sort_order'       => 'ASC',
						'show_option_none' => ' ',
						'class'            => $value['class'],
						'echo'             => false,
						'selected'         => absint( self::get_option( $value['id'] ) ),
					];

					if ( isset( $value['args'] ) ) {
						$args = wp_parse_args( $value['args'], $args );
					}

					?><tr valign="top" class="single_select_page">
						<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?> <?php echo $tooltip_html; ?></th>
						<td class="forminp">
							<?php echo str_replace( ' id=', " data-placeholder='" . esc_attr__( 'Select a page&hellip;', $this->plugin->get_plugin_name() ) . "' style='" . $value['css'] . "' class='" . $value['class'] . "' id=", wp_dropdown_pages( $args ) ); ?> <?php echo $description; ?>
						</td>
					</tr><?php
					break;

				// Single country selects
				case 'single_select_country' :
					$country_setting = (string) self::get_option( $value['id'] );

					if ( strstr( $country_setting, ':' ) ) {
						$country_setting = explode( ':', $country_setting );
						$country         = current( $country_setting );
						$state           = end( $country_setting );
					} else {
						$country = $country_setting;
						$state   = '*';
					}
					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp"><select name="<?php echo esc_attr( $value['id'] ); ?>" style="<?php echo esc_attr( $value['css'] ); ?>" data-placeholder="<?php esc_attr_e( 'Choose a country&hellip;', $this->plugin->get_plugin_name() ); ?>" aria-label="<?php esc_attr_e( 'Country', $this->plugin->get_plugin_name() ) ?>" class="wc-enhanced-select">
							<?php WC()->countries->country_dropdown_options( $country, $state ); ?>
						</select> <?php echo $description; ?>
						</td>
					</tr><?php
					break;

				// Country multiselects
				case 'multi_select_countries' :

					$selections = (array) self::get_option( $value['id'] );

					if ( ! empty( $value['options'] ) ) {
						$countries = $value['options'];
					} else {
						$countries = WC()->countries->countries;
					}

					asort( $countries );
					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp">
							<select multiple="multiple" name="<?php echo esc_attr( $value['id'] ); ?>[]" style="width:350px" data-placeholder="<?php esc_attr_e( 'Choose countries&hellip;', $this->plugin->get_plugin_name() ); ?>" aria-label="<?php esc_attr_e( 'Country', $this->plugin->get_plugin_name() ) ?>" class="wc-enhanced-select">
								<?php
									if ( ! empty( $countries ) ) {
										foreach ( $countries as $key => $val ) {
											echo '<option value="' . esc_attr( $key ) . '" ' . selected( in_array( $key, $selections ), true, false ) . '>' . $val . '</option>';
										}
									}
								?>
							</select> <?php echo ( $description ) ? $description : ''; ?> <br /><a class="select_all button" href="#"><?php _e( 'Select all', $this->plugin->get_plugin_name() ); ?></a> <a class="select_none button" href="#"><?php _e( 'Select none', $this->plugin->get_plugin_name() ); ?></a>
						</td>
					</tr><?php
					break;

				// Default: run an action
				default:
					do_action( 'woocommerce_admin_field_' . $value['type'], $value );
					break;
			}
		}
	}

	/**
	 * Helper function to get the formatted description and tip HTML for a
	 * given form field. Plugins can call this when implementing their own custom
	 * settings types.
	 *
	 * @param  array $value The form field value array
	 * @return array The description and tip as a 2 element array
	 */
	public static function get_field_description( $value ) {
		$description  = '';
		$tooltip_html = '';

		if ( true === $value['desc_tip'] ) {
			$tooltip_html = $value['desc'];
		} elseif ( ! empty( $value['desc_tip'] ) ) {
			$description  = $value['desc'];
			$tooltip_html = $value['desc_tip'];
		} elseif ( ! empty( $value['desc'] ) ) {
			$description  = $value['desc'];
		}

		if ( $description && in_array( $value['type'], [ 'textarea', 'radio' ] ) ) {
			$description = '<p style="margin-top:0">' . wp_kses_post( $description ) . '</p>';
		} elseif ( $description && in_array( $value['type'], [ 'checkbox' ] ) ) {
			$description = wp_kses_post( $description );
		} elseif ( $description ) {
			$description = '<span class="description">' . wp_kses_post( $description ) . '</span>';
		}

		if ( $tooltip_html && in_array( $value['type'], [ 'checkbox' ] ) ) {
			$tooltip_html = '<p class="description">' . $tooltip_html . '</p>';
		} elseif ( $tooltip_html ) {
			$tooltip_html = wc_help_tip( $tooltip_html );
		}

		return [
			'description'  => $description,
			'tooltip_html' => $tooltip_html,
		];
	}

	/**
	 * Save admin fields.
	 *
	 * Loops though the woocommerce options array and outputs each field.
	 *
	 * @param array $options Options array to output
	 * @param array $data Optional. Data to use for saving. Defaults to $_POST.
	 * @return bool
	 */
	public static function save_fields( $options, $data = null ) {
		if ( is_null( $data ) ) {
			$data = $_POST;
		}
		if ( empty( $data ) ) {
			return false;
		}

		// Options to update will be stored here and saved later.
		$update_options = [];

		// Loop options and get values to save.
		foreach ( $options as $option ) {
			if ( ! isset( $option['id'] ) || ! isset( $option['type'] ) ) {
				continue;
			}

			// Get posted value.
			if ( strstr( $option['id'], '[' ) ) {
				parse_str( $option['id'], $option_name_array );
				$option_name  = current( array_keys( $option_name_array ) );
				$setting_name = key( $option_name_array[ $option_name ] );
				$raw_value    = isset( $data[ $option_name ][ $setting_name ] ) ? wp_unslash( $data[ $option_name ][ $setting_name ] ) : null;
			} else {
				$option_name  = $option['id'];
				$setting_name = '';
				$raw_value    = isset( $data[ $option['id'] ] ) ? wp_unslash( $data[ $option['id'] ] ) : null;
			}

			// Format the value based on option type.
			switch ( $option['type'] ) {
				case 'checkbox' :
					$value = '1' === $raw_value || 'yes' === $raw_value ? 'yes' : 'no';
					break;
				case 'textarea' :
					$value = wp_kses_post( trim( $raw_value ) );
					break;
				case 'multiselect' :
				case 'multi_select_countries' :
					$value = array_filter( array_map( 'wc_clean', (array) $raw_value ) );
					break;
				case 'image_width' :
					$value = [];
					if ( isset( $raw_value['width'] ) ) {
						$value['width']  = wc_clean( $raw_value['width'] );
						$value['height'] = wc_clean( $raw_value['height'] );
						$value['crop']   = isset( $raw_value['crop'] ) ? 1 : 0;
					} else {
						$value['width']  = $option['default']['width'];
						$value['height'] = $option['default']['height'];
						$value['crop']   = $option['default']['crop'];
					}
					break;
				case 'select':
					$allowed_values = empty( $option['options'] ) ? [] : array_keys( $option['options'] );
					if ( empty( $option['default'] ) && empty( $allowed_values ) ) {
						$value = null;
						break;
					}
					$default = ( empty( $option['default'] ) ? $allowed_values[0] : $option['default'] );
					$value   = in_array( $raw_value, $allowed_values ) ? $raw_value : $default;
					break;
				default :
					$value = wc_clean( $raw_value );
					break;
			}

			/**
			 * Fire an action when a certain 'type' of field is being saved.
			 * @deprecated 2.4.0 - doesn't allow manipulation of values!
			 */
			if ( has_action( 'woocommerce_update_option_' . sanitize_title( $option['type'] ) ) ) {
				wc_deprecated_function( 'The woocommerce_update_option_X action', '2.4.0', 'woocommerce_admin_settings_sanitize_option filter' );
				do_action( 'woocommerce_update_option_' . sanitize_title( $option['type'] ), $option );
				continue;
			}

			/**
			 * Sanitize the value of an option.
			 * @since 2.4.0
			 */
			$value = apply_filters( 'woocommerce_admin_settings_sanitize_option', $value, $option, $raw_value );

			/**
			 * Sanitize the value of an option by option name.
			 * @since 2.4.0
			 */
			$value = apply_filters( "woocommerce_admin_settings_sanitize_option_$option_name", $value, $option, $raw_value );

			if ( is_null( $value ) ) {
				continue;
			}

			// Check if option is an array and handle that differently to single values.
			if ( $option_name && $setting_name ) {
				if ( ! isset( $update_options[ $option_name ] ) ) {
					$update_options[ $option_name ] = get_option( $option_name, [] );
				}
				if ( ! is_array( $update_options[ $option_name ] ) ) {
					$update_options[ $option_name ] = [];
				}
				$update_options[ $option_name ][ $setting_name ] = $value;
			} else {
				$update_options[ $option_name ] = $value;
			}

			/**
			 * Fire an action before saved.
			 * @deprecated 2.4.0 - doesn't allow manipulation of values!
			 */
			do_action( 'woocommerce_update_option', $option );
		}

		// Save all options in our array.
		foreach ( $update_options as $name => $value ) {
			update_option( $name, $value );
		}

		return true;
	}

	/**
	 * Checks which method we're using to serve downloads.
	 *
	 * If using force or x-sendfile, this ensures the .htaccess is in place.
	 */
	public static function check_download_folder_protection() {
		$upload_dir      = wp_upload_dir();
		$downloads_url   = $upload_dir['basedir'] . '/woocommerce_uploads';
		$download_method = get_option( 'woocommerce_file_download_method' );

		if ( 'redirect' == $download_method ) {

			// Redirect method - don't protect
			if ( file_exists( $downloads_url . '/.htaccess' ) ) {
				unlink( $downloads_url . '/.htaccess' );
			}
		} else {

			// Force method - protect, add rules to the htaccess file
			if ( ! file_exists( $downloads_url . '/.htaccess' ) ) {
				if ( $file_handle = @fopen( $downloads_url . '/.htaccess', 'w' ) ) {
					fwrite( $file_handle, 'deny from all' );
					fclose( $file_handle );
				}
			}
		}
	}
}