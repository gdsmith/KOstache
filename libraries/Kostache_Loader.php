<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Kostache_Loader Library For loading a kostache
 * @package       
 * @author        George McGinley Smith
 * @copyright     (c) Mukuru 2013
 * 
 */
 class Kostache_Loader_Core implements Mustache_Loader, Mustache_Loader_MutableLoader
 {
 	private $_base_dir = 'templates';
 	private $_extension = 'mustache';
 	private $_templates = array();

 	public function __construct($base_dir = NULL, $options = array())
 	{
 		if ($base_dir)
 			$this->_base_dir = $base_dir;

 		if (isset($options['extension']))
 		{
 			$this->_extension = ltrim($options['extension'], '.');
 		}
 	}

 	public function load($name)
 	{
 		if (!isset($this->_templates[$name]))
 		{
 			$this->_templates[$name] = $this->_load_file($name);
 		}

 		return $this->_templates[$name];
 	}

 	protected function _load_file($name)
 	{
 		$filename = Kohana::find_file($this->_base_dir, strtolower($name), FALSE, $this->_extension);

 		if ( ! $filename)
 		{
 			throw new Exception('Mustache template "'.$name.'" not found');
 		}

 		return file_get_contents($filename);
 	}

 	/**
 	 * Set an associative array of Template sources for this loader.
 	 *
 	 * @param array $templates
 	 */
 	public function setTemplates(array $templates)
 	{
 		$this->_templates = array_merge($this->_templates, $templates);
 	}

 	/**
 	 * Set a Template source by name.
 	 *
 	 * @param string $name
 	 * @param string $template Mustache Template source
 	 */
 	public function setTemplate($name, $template)
 	{
 		$this->_templates[$name] = $template;
 	}
 }
  
//  class Mustache_Loader_Core_dummy {
// 	
// 	const VERSION = '2.0.6';
// 
// 	/**
// 	 * Factory method for Kostache views. Accepts a template path and an
// 	 * optional array of partial paths.
// 	 *
// 	 * @param   string  $path     template path
// 	 * @param   array   $partials partial paths
// 	 * @return  Kostache
// 	 * @throws  Kohana_Exception  if the view class does not exist
// 	 */
// 	public static function factory($path, array $partials = NULL)
// 	{
// 		try
// 		{
// 			$class = str_replace('/', '_', $path).'_View';
// 			return new $class(NULL, $partials);
// 		}
// 		catch (Exception $e)
// 		{
// 			throw new Exception("View '$path' not found: ".$e->getMessage());
// 		}
// 	}
// 
// 	/**
// 	 * @var  string  Mustache template
// 	 */
// 	protected $_template;
// 
// 	/**
// 	 * @var  array  Mustache partials
// 	 */
// 	protected $_partials = array();
// 
// 	/**
// 	 * Loads the template and partial paths.
// 	 *
// 	 * @param   string  $path     template path
// 	 * @param   array   $partials partial paths
// 	 * @return  void
// 	 * @uses    Kostache::template
// 	 * @uses    Kostache::partial
// 	 */
// 	public function __construct($template = NULL, array $partials = NULL)
// 	{
// 		if ( ! $template)
// 		{
// 			if ($this->_template)
// 			{
// 				// Load the template defined in the view
// 				$template = $this->_template;
// 			}
// 			else
// 			{
// 				// Detect the template for this class
// 				$template = $this->_detect_template();
// 			}
// 		}
// 
// 		// Load the template
// 		$this->template($template);
// 
// 		if ($this->_partials)
// 		{
// 			foreach ($this->_partials as $name => $path)
// 			{
// 				// Load the partials defined in the view
// 				$this->partial($name, $path);
// 			}
// 		}
// 
// 		if ($partials)
// 		{
// 			foreach ($partials as $name => $path)
// 			{
// 				// Load the partial
// 				$this->partial($name, $path);
// 			}
// 		}
// 	}
// 
// 	/**
// 	 * Magic method, returns the output of [Kostache::render].
// 	 *
// 	 * @return  string
// 	 * @uses    Kostache::render
// 	 */
// 	public function __toString()
// 	{
// 		try
// 		{
// 			return $this->render();
// 		}
// 		catch (Exception $e)
// 		{
// 			ob_start();
// 
// 			// Render the exception
// 			new Kohana_Exception($e);
// 
// 			return (string) ob_get_clean();
// 		}
// 	}
// 
// 	/**
// 	 * Loads a new template from a path.
// 	 *
// 	 * @return  Kostache
// 	 */
// 	public function template($path)
// 	{
// 		$this->_template = $this->_load($path);
// 
// 		return $this;
// 	}
// 
// 	/**
// 	 * Loads a new partial from a path. If the path is empty, the partial will
// 	 * be removed.
// 	 *
// 	 * @param   string  $name  partial name
// 	 * @param   mixed   $path  partial path, FALSE to remove the partial
// 	 * @return  Kostache
// 	 */
// 	public function partial($name, $path)
// 	{
// 		if ( ! $path)
// 		{
// 			unset($this->_partials[$name]);
// 		}
// 		else
// 		{
// 			$this->_partials[$name] = $this->_load($path);
// 		}
// 
// 		return $this;
// 	}
// 
// 	/**
// 	 * Assigns a variable by name.
// 	 *
// 	 *     // This value can be accessed as {{foo}} within the template
// 	 *     $view->set('foo', 'my value');
// 	 *
// 	 * You can also use an array to set several values at once:
// 	 *
// 	 *     // Create the values {{food}} and {{beverage}} in the template
// 	 *     $view->set(array('food' => 'bread', 'beverage' => 'water'));
// 	 *
// 	 * @param   string   $key    variable name or an array of variables
// 	 * @param   mixed    $value  value
// 	 * @return  $this
// 	 */
// 	public function set($key, $value = NULL)
// 	{
// 		if (is_array($key))
// 		{
// 			foreach ($key as $name => $value)
// 			{
// 				$this->{$name} = $value;
// 			}
// 		}
// 		else
// 		{
// 			$this->{$key} = $value;
// 		}
// 
// 		return $this;
// 	}
// 
// 	/**
// 	 * Assigns a value by reference. The benefit of binding is that values can
// 	 * be altered without re-setting them. It is also possible to bind variables
// 	 * before they have values. Assigned values will be available as a
// 	 * variable within the template file:
// 	 *
// 	 *     // This reference can be accessed as {{ref}} within the template
// 	 *     $view->bind('ref', $bar);
// 	 *
// 	 * @param   string   $key    variable name
// 	 * @param   mixed    $value  referenced variable
// 	 * @return  $this
// 	 */
// 	public function bind($key, & $value)
// 	{
// 		$this->{$key} =& $value;
// 
// 		return $this;
// 	}
// 
// 	/**
// 	 * Renders the template using the current view.
// 	 *
// 	 * @return  string
// 	 */
// 	public function render()
// 	{
// 		return $this->_stash($this->_template, $this, $this->_partials)->__toString();
// 	}
// 
// 	/**
// 	 * Return a new Mustache for the given template, view, and partials.
// 	 *
// 	 * @param   string    $template  template
// 	 * @param   Kostache  $view      view object
// 	 * @param   array     $partials  partial templates
// 	 * @return  Mustache
// 	 */
// 	protected function _stash($template, Kostache $view, array $partials)
// 	{
// 		return new Mustache($template, $view, $partials, array(
// 			'charset' => 'UTF-8',
// 		));
// 	}
// 
// 	/**
// 	 * Load a template and return it.
// 	 *
// 	 * @param   string  $path  template path
// 	 * @return  string
// 	 * @throws  Kohana_Exception  if the template does not exist
// 	 */
// 	protected function _load($path)
// 	{
// 		$file = Kohana::find_file('templates', $path, FALSE, 'mustache');
// 
// 		if ( ! $file)
// 		{
// 			throw new Kohana_Exception('Template file does not exist: templates/'.$path);
// 		}
// 
// 		return file_get_contents($file);
// 	}
// 
// 	/**
// 	 * Detect the template name from the class name.
// 	 *
// 	 * @return  string
// 	 */
// 	protected function _detect_template()
// 	{
// 		// Start creating the template path from the class name
// 		$template = explode('_', get_class($this));
// 
// 		// Remove "View" suffix
// 		array_pop($template);
// 
// 		// Convert name parts into a path
// 		$template = strtolower(implode('/', $template));
// 
// 		return $template;
// 	}
// 
// } // End Kostache_Loader Library
