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
			array_pop($template);
			$template = implode('/', $template);
		}
		return $this->_engine->loadTemplate($template)->render($class);
	}
} // End KOstache Library
