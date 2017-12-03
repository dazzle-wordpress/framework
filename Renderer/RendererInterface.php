<?php
/**
 * The public-facing functionality of the plugin Renderer Package
 *
 * @link       https://dazzlesoftware.org
 * @since      2.0.0
 *
 * @package    Dazzle\Fusion\Renderer
 * @subpackage Dazzle\Fusion\Renderer\RendererInterface
 */

namespace Dazzle\Fusion\Renderer;

/**
 * Interface for a renderer which can have template paths added during runtime.
 *
 * @package    Dazzle\Fusion\Renderer
 * @subpackage Dazzle\Fusion\Renderer\RendererInterface
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
interface RendererInterface
{
	/**
	 * Checks if folder, folder alias, template or template path exists
	 *
	 * @param   string  $path  Full path or part of a path
	 *
	 * @return  boolean  True if the path exists
	 *
	 * @since   2.0.0
	 */
	public function pathExists(string $path);

	/**
	 * Get the rendering engine
	 *
	 * @return  mixed
	 *
	 * @since   2.0.0
	 */
	public function getRenderer();

	/**
	 * Render and return compiled data.
	 *
	 * @param   string  $template  The template file name
	 * @param   array   $data      The data to pass to the template
	 *
	 * @return  string  Compiled data
	 *
	 * @since   2.0.0
	 */
	public function render(string $template, array $data = array());

	/**
	 * Sets a piece of data
	 *
	 * @param   string  $key    Name of variable
	 * @param   string  $value  Value of variable
	 *
	 * @return  $this
	 *
	 * @since   2.0.0
	 */
	public function set(string $key, $value);

	/**
	 * Loads data from array into the renderer
	 *
	 * @param   array  $data  Array of variables
	 *
	 * @return  $this
	 *
	 * @since   2.0.0
	 */
	public function setData(array $data);

	/**
	 * Unloads data from renderer
	 *
	 * @return  $this
	 *
	 * @since   2.0.0
	 */
	public function unsetData();
}
