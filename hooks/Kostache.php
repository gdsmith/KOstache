<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * autoloader for kostache views 
 * we need this so that views can be extended
 *
 * @package default
 * @author George McGinley Smith
 */
function Kostache_auto_load($class)
{
	if (class_exists($class) || substr($class, -5) != '_View' )
		return;

	$path = str_replace('_', '/', substr($class, 0, -5));
	$file = Kohana::find_file('views', $path);
	if ( ! $file)
	{
		throw new Exception("Class '$class' not found");
	}
	else
	{
		include_once($file);
	}
}

// register autoloader with the following options:
// don't throw exceptions and prepend this to the register stack (so Swift doesn't throw a wobbly)
spl_autoload_register('Kostache_auto_load', FALSE, TRUE);
