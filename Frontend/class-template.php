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
class Fusion_Template
{
	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Fusion $plugin This plugin's instance.
	 */
	public $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * 
	 * @param Fusion $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin )
	{
		$this->plugin = $plugin;
	}

	/**
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. woocommerce looks for theme.
	 * overrides in /theme/woocommerce/ by default.
	 *
	 * For beginners, it also looks for a woocommerce.php template first. If the user adds.
	 * this to the theme (containing a woocommerce() inside) this will be used for all.
	 * woocommerce templates.
	 *
	 * @param mixed $template
	 * @return string
	 */
	public function template_loader( $template ) {
		if ( is_embed() ) {
			return $template;
		}

		if ( $default_file = $this->get_template_loader_default_file() ) {
			/**
			 * Filter hook to choose which files to find before WooCommerce does it's own logic.
			 *
			 * @since 3.0.0
			 * @var array
			 */
			$search_files = $this->get_template_loader_files( $default_file );
			$template     = locate_template( $search_files );

			if ( ! $template || $this->plugin->get_template_debug() ) {
				$template = $this->plugin->get_plugin_path() . 'templates/' . $default_file;
			}
		}

		return $template;
	}

	/**
	 * Get the default filename for a template.
	 *
	 * @since  3.0.0
	 * @return string
	 */
	private function get_template_loader_default_file()
	{
		foreach ($this->plugin->config->get_post_type() as $posts => $post)
		{
			if ( is_singular( $post['post_type'] ) )
			{
				$default_file = 'single-' . $post['post_type'] . '.php';
			}
			foreach ($this->plugin->config->get_taxonomies() as $taxonomies => $taxonomy)
			{
				if ( is_tax( get_object_taxonomies( $taxonomy['post_type'] ) ) )
				{
					$term = get_queried_object();

					if ( is_tax( $taxonomy['taxonomy'] ) )
					{
						$default_file = 'taxonomy-' . $term->taxonomy . '.php';
					}
					else
					{
						$default_file = 'archive-' . $post['post_type'] . '.php';
					}
				}
			}
			// @todo fix me wc_get_page_id once setting page has been created.
			if ( is_post_type_archive( $post['post_type'] ) /*|| is_page( $this->plugin->core->get_pages()->get_page_id( $post['post_type'] ) )*/ ) { 
				$default_file = 'archive-' . $post['post_type'] . '.php';
			}
			else
			{
				$default_file = '';
			}
			return $default_file;
		}
	}

	/**
	 * Get an array of filenames to search for a given template.
	 *
	 * @since  3.0.0
	 * @param  string $default_file The default file name.
	 * @return string[]
	 */
	private function get_template_loader_files( $default_file ) {
		$search_files   = apply_filters( $this->plugin->get_plugin_prefix() . '_template_loader_files', [], $default_file );

		if ( is_page_template() ) {
			$search_files[] = get_page_template_slug();
		}
		foreach ($this->plugin->config->get_post_type() as $posts => $post)
		{
			if ( is_tax( get_object_taxonomies( $post['post_type'] ) ) ) {
				$term   = get_queried_object();
				$search_files[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
				$search_files[] = $this->plugin->template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
				$search_files[] = 'taxonomy-' . $term->taxonomy . '.php';
				$search_files[] = $this->plugin->template_path() . 'taxonomy-' . $term->taxonomy . '.php';
			}
		}

		$search_files[] = $default_file;
		$search_files[] = $this->plugin->template_path() . $default_file;

		return array_unique( $search_files );
	}

	/**
	 * Load comments template.
	 *
	 * @param mixed $template
	 * @return string
	 */
	public function comments_template_loader( $template )
	{
		foreach ($this->plugin->config->get_post_type() as $posts => $post)
		{
			if ( get_post_type() !== $post['post_type'] ) {
				return $template;
			}

			$check_dirs = [
				trailingslashit( get_stylesheet_directory() ) . $this->plugin->template_path(),
				trailingslashit( get_template_directory() ) . $this->plugin->template_path(),
				trailingslashit( get_stylesheet_directory() ),
				trailingslashit( get_template_directory() ),
				trailingslashit( $this->plugin->get_plugin_path() ) . 'templates/',
			];

			if ( $this->plugin->get_template_debug() ) {
				$check_dirs = [ array_pop( $check_dirs ) ];
			}

			foreach ( $check_dirs as $dir ) {
				if ( file_exists( trailingslashit( $dir ) . 'single-' . $post['post_type'] . '-reviews.php' ) ) {
					return trailingslashit( $dir ) . 'single-' . $post['post_type']. '-reviews.php';
				}
			}
		}
	}
}
