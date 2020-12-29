<?php

namespace App\Http\Controllers\Api\ElasticSearch;

use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

class ESController extends Controller
{
    //
    private $client;
    public function __construct()
    {
         $hosts = [
            '192.168.20.89:9200',
        ];
        $this->client = ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($hosts)      // Set the hosts
        ->build();
    }
}
