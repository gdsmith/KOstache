<?php defined('SYSPATH') OR die('No direct script access.');

$config = array(
	'loader' => new Kostache_Loader(),
	'partials_loader' => new Kostache_Loader('templates/partials'),
	'escape' => function($value) {
		return html::specialchars($value);
	},
	'cache' => APPPATH.'cache/mustache',
	'logger' => new Kostache_Logger(Mustache_Logger::INFO),
);
