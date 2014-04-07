<?php

namespace Proudlygeek;

use Requests;

class HttpClient {
	/**
	 * Makes a GET request to an endpoint.
	 * The client assumes that the server will return
	 * a JSON response.
	 * 
	 * @param  string $url The Endpoint URL 
	 * @return string
	 *
	 */
	public function get($url) 
	{
		return Requests::get($url, array('Accept' => 'application/json'));
	}

	/**
	 * Makes a PUT request to a specific ETCD key.
	 * Value must be in the payload as:
	 *
	 * value={...}
	 * 
	 * @param  string $url   The endpoint URL
	 * @param  string $value Value of the key
	 * @return string
	 */
	public function put($url, $value)
	{
		return Requests::put($url, array('Accept' => 'application/json'), array('value' => $value));
	}
}