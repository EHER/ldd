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
            "PATH_INFO" => "/test/path",
        );
        $this->request->proccessRequest($request);
        $this->assertEquals('{"verb":"get","path":"\/test\/path"}', $this->request->toJson());
    }
}
