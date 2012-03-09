<?php

namespace Eher\Ldd;

use Guzzle\Service\Client;

class Request
{
    private $verb;
    private $protocol;
    private $hostname;
    private $path;
    private $parameters;

    public function __construct($request = null, $parameters = null)
    {
        if ($request != null) {
            $this->proccessRequest($request);
        }

        if ($parameters != null) {
            $this->proccessParameters($parameters);
        }
    }

    public function proccessRequest($request)
    {
        $this->verb = strtolower($request['REQUEST_METHOD']);
        list($protocolName, $protocolVersion) = explode("/", $request['SERVER_PROTOCOL']);
        $this->protocol = strtolower($protocolName);
        $this->hostname = strtolower($request['HTTP_HOST']);
        $this->path = strtolower($request['PATH_INFO']);
    }

    public function proccessParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    public function execute($client = null)
    {
        if ($client == null) {
            $client = new Client();
        }

        $url = $this->protocol . '://' . $this->hostname . '/' . $this->path;

        switch($this->verb) {
        case 'get':
            $request = $client->get($url);
            break;
        case 'post':
            $request = $client->post($url);
            break;
        case 'put':
            $request = $client->put($url);
            break;
        case 'delete':
            $request = $client->delete($url);
            break;
        }

        return new Response($request->send());
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        return array(
            "verb" => $this->verb,
            "protocol" => $this->protocol,
            "hostname" => $this->hostname,
            "path" => $this->path,
            "parameters" => $this->parameters,
        );
    }
}
