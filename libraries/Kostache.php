<?php defined('SYSPATH') OR die('No direct access allowed.');

// Load Mustache for PHP
require_once Kohana::find_file('vendor', 'mustache/src/Mustache/Autoloader');
Mustache_Autoloader::register();


/**
 * Mustache templates for Kohana.
 *
 * @package    Kostache
 * @category   Base
 * @author     Jeremy Bush <jeremy.bush@kohanaframework.org>
 * @author     Woody Gilk <woody.gilk@kohanaframework.org>
 * @author     George McGinley Smith <george@mukuru.com>
 * @copyright  (c) 2010-2012 Jeremy Bush
 * @copyright  (c) 2011-2012 Woody Gilk
 * @license    MIT
 */
class Kostache_Core {
	
	const VERSION = '4.0.0';

	protected $_engine;

	public static function factory($config = NULL)
	{
		if ( ! $config)
		{
			$config = Kohana::config('mustache', array(
 				'loader' => new Kostache_Loader(),
 				'partials_loader' => new Kostache_Loader('templates/partials'),
 				'escape' => function($value) {
 					return html::specialchars($value);
 				},
 				'cache' => APPPATH.'cache/mustache',
 				'logger' => new Kostache_Logger(Mustache_Logger::INFO),
			 ));
		}
		$m = new Mustache_Engine($config);

		$class = get_called_class();
		return new $class($m);
	}

	public function __construct($engine)
	{
		$this->_engine = $engine;
	}

	public function render($class, $template = NULL)
	{
		if ($template == NULL)
		{
			$template = explode('_', get_class($class));
			// View_viewname_Core allows overriding
			if (array_key_exists('Core', $template))
			{
				// remove _Core
				array_pop($template);
			}
			// remove View_
			array_shift($template);
			$template = implode('/', $template);
		}
		return $this->_engine->loadTemplate($template)->render($class);
	}
	
	/**
	 * autoloader for kostache views 
	 * we need this so that views can be extended
	 *
	 * @package default
	 * @author George McGinley Smith
	 */
	public static function auto_load($class)
	{
		if (class_exists($class) OR substr($class, 0, 5) != 'View_')
			return;
		
		$type = 'k_views';
		if (($suffix = strrpos($class, '_')) > 0)
		{
			// Find the class suffix
			$suffix = substr($class, $suffix + 1);
		}
		else
		{
			// No suffix
			$suffix = FALSE;
		}

		if ($suffix === 'Core')
		{
			$file = substr($class, 5, -5);
		}
		else
		{
			$file = substr($class, 5);
		}
		
		$file = str_replace('_', '/', $file);

		if ($filename = Kohana::find_file($type, $file))
		{
			// Load the class
			require $filename;
		}
		else
		{
			// The class could not be found
			throw new Exception('No views/'.$path.'.php for class '.$class);
			// return FALSE;
		}

		$path = explode('/', $file);
		$prefixed = Kohana::config('core.extension_prefix').array_pop($path);
		$prefixed = implode('/', $path).'/'.$prefixed;

		if ($filename = Kohana::find_file($type, $prefixed))
		{
			// Load the class extension
			require $filename;
		}
		elseif ($suffix !== 'Core' AND class_exists($class.'_Core', FALSE))
		{
			// Class extension to be evaluated
			$extension = 'class '.$class.' extends '.$class.'_Core { }';

			// Start class analysis
			$core = new ReflectionClass($class.'_Core');

			if ($core->isAbstract())
			{
				// Make the extension abstract
				$extension = 'abstract '.$extension;
			}

			// Transparent class extensions are handled using eval. This is
			// a disgusting hack, but it gets the job done.
			eval($extension);
		}
		
		return TRUE;

	}

} // End KOstache Library
