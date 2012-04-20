<?php

/**
 * @author Bennet Matschullat <hello@bennet.matschullat.com>
 * @since 20.04.2012 - 22:53:55
 */

require_once __DIR__.'/Hetzner/Api/TestCase.php';

spl_autoload_register(function($class)
{
	$file = __DIR__.'/../src/'.strtr($class, '\\', '/').'.php';
	if (file_exists($file)) {
		require $file;
		return true;
	}
});


/** End of file: bootstrap.php */