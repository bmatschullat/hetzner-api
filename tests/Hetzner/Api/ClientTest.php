<?php

namespace Hetzner\Api;

/**
 * @author Bennet Matschullat <hello@bennet.matschullat.com>
 * @since 20.04.2012 - 22:58:37
 */

use \Hetzner\Api\Client;

class ClientTest extends \PHPUnit_Framework_TestCase 
{
	
	public function testGetName()
	{
		$ws = new Client('', '', FALSE);
		$servers = $ws->getServers();
		$this->assertObjectHasAttribute('server_name', $servers[0]->server);
	}
	
	
	
}


/** End of file: ClientTest.php */
