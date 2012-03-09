<?php

namespace Eher\Ldd;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    private $request;

    public function Setup()
    {
        $this->request = new Request();
    }

    public function TearDown()
    {
        unset($this->request);
    }

    public function testProccessRequest()
    {
        $request = array(
            "REQUEST_METHOD" => "GET",
            "SERVER_PROTOCOL" => "HTTP/1.1",
            "HTTP_HOST" => "eher.com.br",
            "PATH_INFO" => "/test/path",
        );
        $this->request->proccessRequest($request);
        $this->assertEquals(
            '{"verb":"get","protocol":"http","hostname":"eher.com.br","path":"\/test\/path","parameters":null}',
            $this->request->toJson()
        );
    }

    public function testProccessRequestWithParameters()
    {
        $request = array(
            "REQUEST_METHOD" => "GET",
            "SERVER_PROTOCOL" => "HTTP/1.1",
            "HTTP_HOST" => "eher.com.br",
            "PATH_INFO" => "/test/path",
        );
        $parameters = array(
            "eher" => "test",
        );
        $this->request->proccessRequest($request);
        $this->request->proccessParameters($parameters);
        $this->assertEquals(
            '{"verb":"get","protocol":"http","hostname":"eher.com.br","path":"\/test\/path","parameters":{"eher":"test"}}',
            $this->request->toJson()
        );
    }
}
