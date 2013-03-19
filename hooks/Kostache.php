<?php defined('SYSPATH') OR die('No direct access allowed.');

// register autoloader with the following options:
// don't throw exceptions and prepend this to the register stack (so Swift doesn't throw a wobbly)
spl_autoload_register(array('Kostache', 'auto_load'), FALSE, TRUE);
