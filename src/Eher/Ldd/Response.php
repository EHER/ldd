<?php

namespace Eher\Ldd;

class Response
{
    private $statusCode;
    private $body;

    public function __construct($response)
    {
        $this->statusCode = $response->getStatusCode();
        $this->body = $response->getReasonPhrase();
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getBody()
    {
        return $this->body;
    }
}
