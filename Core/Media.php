<?php

/**
 * The media core functionality of the plugin.
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
 * The media core functionality of the plugin.
 *
 * Defines the plugin name, Images, stylesheet and JavaScript.
 *
 * @package    Fusion\Products
 * @subpackage Fusion\Products\Frontend
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class Media
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
	 * The plugin's queued JavaScript.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin $queued_js This plugin's instance.
	 */
	public $queued_js;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * 
	 * @param Plugin $plugin This plugin's  queued JavaScript.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Get an image size.
	 *
	 * Variable is filtered by $prefix_get_image_size_{image_size}.
	 *
	 * @param array|string $image_size
	 * @return array
	 */
	function get_image_size( $image_size ) // wc_get_image_size( $image_size )
	{
		if ( is_array( $image_size ) )
		{
			$width  = isset( $image_size[0] ) ? $image_size[0] : '300';
			$height = isset( $image_size[1] ) ? $image_size[1] : '300';
			$crop   = isset( $image_size[2] ) ? $image_size[2] : 1;

			$size = array(
				'width'  => $width,
				'height' => $height,
				'crop'   => $crop,
			);

			$image_size = $width . '_' . $height;

		}
		elseif ( in_array( $image_size, array( 'shop_thumbnail', 'shop_catalog', 'shop_single' ) ) )
		{
			$size           = get_option( $image_size . '_image_size', array() );
			$size['width']  = isset( $size['width'] ) ? $size['width'] : '300';
			$size['height'] = isset( $size['height'] ) ? $size['height'] : '300';
			$size['crop']   = isset( $size['crop'] ) ? $size['crop'] : 0;

		}
		else
		{
			$size = array(
				'width'  => '300',
				'height' => '300',
				'crop'   => 1,
			);
		}

		return apply_filters( $this->plugin->get_plugin_prefix() . '_get_image_size_' . $image_size, $size );
	}

	/**
	 * Queue some JavaScript code to be output in the footer.
	 *
	 * @param string $code
	 */
	function enqueue_js( $code ) // wc_enqueue_js( $code )
	{
		if ( empty( $this->queued_js ) )
		{
			$this->queued_js = '';
		}

		$this->queued_js .= "\n" . $code . "\n";
	}

	/**
	 * Output any queued javascript code in the footer.
	 */
	function print_js() // wc_print_js()
	{
		if ( ! empty( $this->queued_js ) ) {
		// Sanitize.
		$this->queued_js = wp_check_invalid_utf8( $this->queued_js );
		$this->queued_js = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", $this->queued_js );
		$this->queued_js = str_replace( "\r", '', $this->queued_js );

		$js = "<!-- WooCommerce JavaScript -->\n<script type=\"text/javascript\">\njQuery(function($) { $this->queued_js });\n</script>\n";

		/**
		 * queued_js filter.
		 *
		 * @since 2.6.0
		 * @param string $js JavaScript code.
		 */
		echo apply_filters( $this->plugin->get_plugin_prefix() . '_queued_js', $js );

		unset( $this->queued_js );
	}
}
}
