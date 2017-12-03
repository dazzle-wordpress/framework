<?php
/**
 * Fired during plugin Twig renderer.
 *
 * @link       https://dazzlesoftware.org
 * @since      2.0.0
 *
 * @package    Dazzle\Fusion
 * @subpackage PluginName/includes
 */

namespace Dazzle\Fusion\Renderer;

use Dazzle\Fusion\Plugin;

/**
 * Fired during plugin Twig renderer.
 *
 * This class defines all code necessary to run during the Twig renderering.
 *
 * @since      2.0.0
 * @package    Dazzle\Fusion\Renderer
 * @subpackage Dazzle\Fusion\TwigRenderer
 * @author     Dazzle Software <support@dazzlesoftware.org>
 */
class TwigRenderer extends AbstractRenderer implements AddTemplateFolderInterface
{
	/**
	 * Rendering engine
	 *
	 * @var    \Twig_Environment
	 * @since  2.0.0
	 */
	private $renderer;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.0.0
	 * 
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( \Twig_Environment $renderer = null )
	{
		$this->renderer = $renderer ? : new \Twig_Environment(new \Twig_Loader_Filesystem);
	}

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
	public function addFolder(string $directory, string $alias = '')
	{
		$loader = $this->getRenderer()->getLoader();
		// This can only be reliably tested with a loader using the filesystem loader's API
		if (method_exists($loader, 'addPath'))
		{
			if ($alias === '')
			{
				$alias = \Twig_Loader_Filesystem::MAIN_NAMESPACE;
			}
			$loader->addPath($directory, $alias);
		}
		return $this;
	}

    /**
     * Registers a Global.
     *
     * New globals can be added before compiling or rendering a template;
     * but after, you can only update existing globals.
     *
     * @param string $name  The global name
     * @param mixed  $value The global value
     */
	public function addGlobal($name, $value)
	{
		$loader = $this->getRenderer()->getLoader();
		// This can only be reliably tested with a loader using the filesystem loader's API
		if (method_exists($loader, 'addGlobal'))
		{
			$loader->addGlobal($name, $value);
		}
		return $this;
	}

	/**
	 * Get the rendering engine
	 *
	 * @return  \Twig_Environment
	 *
	 * @since   2.0.0
	 */
	public function getRenderer()
	{
		return $this->renderer;
	}

	/**
	 * Checks if folder, folder alias, template or template path exists
	 *
	 * @param   string  $path  Full path or part of a path
	 *
	 * @return  boolean  True if the path exists
	 *
	 * @since   2.0.0
	 */
	public function pathExists(string $path)
	{
		$loader = $this->getRenderer()->getLoader();
		/*
		 * For Twig 1.x compatibility, check if the loader implements Twig_ExistsLoaderInterface
		 * As of Twig 2.0, the `exists()` method is part of Twig_LoaderInterface
		 * This conditional may be removed when dropping Twig 1.x support
		 */
		if ($loader instanceof \Twig_ExistsLoaderInterface || method_exists('Twig_LoaderInterface', 'exists'))
		{
			return $loader->exists($path);
		}
		// For all other cases we'll assume the path exists
		return true;
	}

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
	public function render(string $template, array $data = array())
	{
		$data = array_merge($this->data, $data);
		return $this->getRenderer()->render($template, $data);
	}
}
