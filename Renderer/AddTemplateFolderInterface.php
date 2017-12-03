<?php
/**
 * The public-facing functionality of the plugin Renderer Package
 *
 * @link       https://dazzlesoftware.org
 * @since      2.0.0
 *
 * @package    Dazzle\Fusion\Renderer
 * @subpackage Dazzle\Fusion\Renderer\AddTemplateFolderInterface
 */

namespace Dazzle\Fusion\Renderer;

/**
 * Interface for a renderer which can have template paths added during runtime.
 *
 * @package    Dazzle\Fusion\Renderer
 * @subpackage Dazzle\Fusion\Renderer\AddTemplateFolderInterface
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
interface AddTemplateFolderInterface
{
	/**
	 * Add a folder with alias to the renderer
	 *
	 * @param   string  $directory  The folder path
	 * @param   string  $alias      The folder alias
	 *
	 * @return  $this
	 *
	 * @since   2.0.0
	 */
	public function addFolder(string $directory, string $alias = '');
}
