<?php

namespace Eher\Ldd;

class Request
{
    private $verb;
    private $path;
    public function __construct($request = null)
    {
        if ($request != null) {
            $this->proccessRequest($request);
        }
    }

    public function proccessRequest($request)
    {
        $this->verb = strtolower($request['REQUEST_METHOD']);
        $this->path = strtolower($request['PATH_INFO']);
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
        );
    }
}
