<?php

namespace Eher\Ldd;

class Request
{
    private $verb;
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
            "path" => $this->path,
            "parameters" => $this->parameters,
        );
    }
}
