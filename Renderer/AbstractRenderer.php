<?php
/**
 * The public-facing functionality of the plugin Renderer Package.
 *
 * @link       https://dazzlesoftware.org
 * @since      2.0.0
 *
 * @package    Dazzle\Fusion\Renderer
 * @subpackage Dazzle\Fusion\AbstractRenderer
 */

namespace Dazzle\Fusion\Renderer;

/**
 * Abstract class for templates renderer.
 *
 * This class defines all code necessary to run during the templates renderer.
 *
 * @since      2.0.0
 * @package    Dazzle\Fusion\Renderer
 * @subpackage Dazzle\Fusion\AbstractRenderer
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
abstract class AbstractRenderer implements RendererInterface
{
	/**
	 * Data for output by the renderer
	 *
	 * @var    array
	 * @since  2.0.0
	 */
	protected $data = [];

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
	public function set(string $key, $value)
	{
		$this->data[$key] = $value;

		return $this;
	}

	/**
	 * Loads data from array into the renderer
	 *
	 * @param   array  $data  Array of variables
	 *
	 * @return  $this
	 *
	 * @since   2.0.0
	 */
	public function setData(array $data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * Unloads data from renderer
	 *
	 * @return  $this
	 *
	 * @since   2.0.0
	 */
	public function unsetData()
	{
		$this->data = [];

		return $this;
	}
}
