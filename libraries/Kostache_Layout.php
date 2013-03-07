<?php defined('SYSPATH') OR die('No direct script access.');
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
class Kostache_Layout_Core extends Kostache {

	/**
	 * @var  string  partial name for content
	 */
	const CONTENT_PARTIAL = 'content';

	/**
	 * @var  string  layout path
	 */
	protected $_layout = 'layout';

	public static function factory($layout = 'layout')
	{
		$k = parent::factory();
		$k->set_layout($layout);
		return $k;
	}

	public function set_layout($layout)
	{
		$this->_layout = (string) $layout;
	}

	public function render($class, $template = NULL)
	{
		$this->_engine->setPartials(
			array(
				Kostache_Layout::CONTENT_PARTIAL => parent::render($class, $template)
			)
		);

		return $this->_engine->loadTemplate($this->_layout)->render($class);
	}

}
