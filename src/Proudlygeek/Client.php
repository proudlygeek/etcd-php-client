<?php

namespace Proudlygeek;

use Requests;
use Proudlygeek\Exception\KeyNotCreatedException;
use Proudlygeek\Exception\KeyNotFoundException;


class Client
{
	private $server;
	private $port;
	private $options;
	private $host;
	private $client;


	/**
	 * Instantiate an ETCD client given an host and a port.
	 *
	 * Several options can be specified in the $options array:
	 *
	 * 1.  SSL - Enables SSL communication on endpoint
	 * 
	 * @param string  $server  An ETCD server endpoint (Ex. http://etcd.dev.loc)
	 * @param integer $port    A server port (ex: 4444)
	 * @param array   $options An array of options
	 */
	public function __construct($server='127.0.0.1', $port=4001, $options=array(), HttpClient $client=null)
	{
		$this->server = $server;
		$this->port = $port;
		$this->options = $options;
		$this->client = (is_null($client)) ? new HttpClient() : $client;

		$this->host = ((preg_match('/^http/', $this->server)) 
			? ''
			: 'http://') . $this->server . ':' . $this->port . '/v2/keys';
	}

	public function getServer()
	{
		return $this->server;
	}

	public function getPort()
	{
		return $this->port;
	}

	public function getOptions()
	{
		return $this->options;
	}

	public function getHost()
	{
		return $this->host;
	}

	/**
	 * Retrieves an ETCD key given a relative path.
	 *
	 * For example:
	 *
	 * $client->get('/services/hello');
	 *
	 * @throws KeyNotFoundException Key not found
	 * @param  string $key  		Relative key path
	 * @return string         
	 */
    public function get($key)
    {
    	try {
	    	$response = $this->client->get($this->host . $key);
	    	return json_decode($response->body, true);
    	} catch (\Requests_Exception_HTTP_404 $e) {
    		throw new KeyNotFoundException($e->getMessage());
    	}
    }

	/**
	 * Sets a key given a path and a value.
	 *
	 * For example:
	 *
	 * $client->set('/services/cat', 'meow');
	 *
	 * @throws KeyNotCreatedException Incorrect key creation (res code not 201)
	 * @param string $key   Relative key path
	 * @param array $value  The value to be setted on this key
	 */
    public function set($key, $value)
    {
    	try {
    		$response = $this->client->put($this->host . $key, $value);
    	} catch (\Requests_Exception $e) {
    		throw new KeyNotCreatedException("The key was not setted: " . $e->getReason());
    	}

    	return json_decode($response->body, true);
    }
}
