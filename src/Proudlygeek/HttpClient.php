<?php

namespace Proudlygeek;

use Requests;

class HttpClient {
	public function get($url) {
		return Requests::get($url, array('Accept' => 'application/json'));
	}
}