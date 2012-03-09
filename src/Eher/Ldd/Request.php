<?php

namespace Eher\Ldd;

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
