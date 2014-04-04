<?php

require 'vendor/autoload.php';

use Proudlygeek\Client;

$client = new Client('http://etcd.vagrant.dev', 4001);

$result = $client->get('/services/search/deferAdSearchResult');

print_r($result);
