<?php

namespace Hetzner\Api;
/**
 * @author Bennet Matschullat <hello@bennet.matschullat.com>
 * @since 20.04.2012 - 22:10:47
 * 
 * 
 * For the full copyright and license information, please view the LICENSE.mdown
 * file that was distributed with this source code.
 */


class Client {
	
	protected $_webservice_url = 'https://robot-ws.your-server.de/';
	
	protected $_curl = null;
	
	protected $_http_headers = array();
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $login
	 * @param string $password
	 * @param $verbose
	 */
	public function __construct($login, $password, $verbose)
	{
		$this->_curl = curl_init();
		$this->auth($login, $password);
		curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->_curl, CURLOPT_VERBOSE, $verbose);
	}
	
	
	/**
	 * 
	 * close open curl session
	 */
	public function __destruct()
	{
		curl_close($this->_curl);
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $url
	 * @param string $type
	 * @throws \Hetzner\Api\WebserviceException
	 */
	protected function _request($url, $type = 'GET')
	{
		switch ($type) {
			case 'POST':
				curl_setopt($this->_curl, CURLOPT_POST, true);
				break;
				
			case 'PUT':
			case 'DELETE':
			case 'GET':
				curl_setopt($this->_curl, CURLOPT_HTTPGET, true);
				break;
				
			default:
				throw new \Hetzner\Api\WebserviceException('wrong request type [PUT,DELETE,GET,POST]');
				break;
		}
		curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, $type);
		
		curl_setopt($this->_curl, CURLOPT_URL, $url);
		curl_setopt($this->_curl, CURLOPT_HTTPHEADER, $this->getHttpHeaders());

		# send request
		$response = curl_exec($this->_curl);
		$response_code = curl_getinfo($this->_curl, CURLINFO_HTTP_CODE);
		
		if (FALSE === $response)
		{
			throw new \Hetzner\Api\WebserviceException('NOT_REACHABLE');
		}
		
		
		if ($response == '')
		{
			$response = new \stdClass();
		}
		else
		{
			$response = json_decode($response);
		}
		
		if (NULL === $response)
		{
			throw new \Hetzner\Api\WebserviceException('RESPONSE_DECODE_ERROR');
		}
		
		if ($response_code >= 400 && $response_code <= 503)
		{
			throw new \Hetzner\Api\WebserviceException($response->error->message. ' {'. $response->error->code . '}', $response_code);
		}
		
		return array(
					'response' => $response,
					'resonse_code' => $response_code,
					);
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function getHttpHeaders()
	{
		return $this->_http_headers;	
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $headers
	 */
	public function setHttpHeaders(array $headers)
	{
		$this->_http_headers = $headers;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $login
	 * @param string $password
	 */
	public function auth($login, $password)
	{
		curl_setopt($this->_curl, CURLOPT_USERPWD, $login . ':' . $password);
	}
	

	/**
	 * 
	 * list of all servers
	 */
	public function getServers()
	{
		$response = $this->_request($this->_webservice_url . 'server/');
		return $response['response'];
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $ip
	 * @throws \Hetzner\Api\WebserviceException
	 */
	public function getServerInformations($ip)
	{
		if(filter_var($ip, FILTER_VALIDATE_IP))
		{
			$response = $this->_request($this->_webservice_url . 'server/' . $ip);
			return $response['response']->server;
		}
		else throw new \Hetzner\Api\WebserviceException('ip is invalid');
	}
	
	
}


/** End of file: Api.php */
