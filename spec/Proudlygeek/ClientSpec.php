<?php

namespace spec\Proudlygeek;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Proudlygeek\HttpClient;
use Requests;

class ClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Proudlygeek\Client');
    }

    function it_should_init_with_defaults()
    {
    	$this->beConstructedWith();

    	$this->getServer()->shouldEqual('127.0.0.1');
    	$this->getPort()->shouldEqual(4001);
    	$this->getOptions()->shouldEqual(array());
    	$this->getHost()->shouldEqual('http://127.0.0.1:4001/v2/keys');
    }

    function it_should_init_with_params()
    {
    	$server = 'http://etcd.example.lo';
    	$port = 9999;
    	$options = array('SSL' => true);
    	$this->beConstructedWith($server, $port, $options);

    	$this->getServer()->shouldEqual($server);
    	$this->getPort()->shouldEqual($port);
    	$this->getOptions()->shouldEqual($options);
    	$this->getHost()->shouldEqual('http://etcd.example.lo:9999/v2/keys');
    }

    function it_should_get_a_key(HttpClient $client)
    {
    	$server = 'http://etcd.example.lo';
    	$port = 9999;
    	$options = array('SSL' => true);
    	$this->beConstructedWith($server, $port, $options, $client);

    	$json = json_encode(array(
    		'action' => 'get',
    		'node' => array(
    			'key' => '/sample',
    			'value' => 'testValue'
    		)
    	));

    	$response = new \Requests_Response();
    	$response->status = 200;
    	$response->body = $json;

    	$client->get('http://etcd.example.lo:9999/v2/keys/sample')->willReturn($response);

    	$this->get('/sample')->shouldEqual(json_decode($json, true));
    }

    function it_should_throw_an_exception_if_key_is_not_present(HttpClient $client)
    {
    	$server = 'http://etcd.example.lo';
    	$port = 9999;
    	$options = array('SSL' => true);
    	$this->beConstructedWith($server, $port, $options, $client);

    	$response = new \Requests_Response();
    	$response->status = 404;
    	$response->body = "Not Found";

    	$client->get('http://etcd.example.lo:9999/v2/keys/nonExistingKey')->willThrow('Requests_Exception_HTTP_404');

    	$this->shouldThrow('Proudlygeek\Exception\KeyNotFoundException')->during('get', array('/nonExistingKey'));

    }
}
