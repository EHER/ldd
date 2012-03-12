<?php

namespace Eher\Ldd;

use Guzzle\Service\Client as GuzzleClient;
use Predis\Client as PredisClient;
use Guzzle\Http\Message\Response as GuzzleResponse;

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

    public function testExecuteRequest()
    {
        $responseMock = $this->getMock("GuzzleResponse", array("getStatusCode", "getReasonPhrase"));
        $responseMock->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue("200"));
        $responseMock->expects($this->any())
            ->method('getReasonPhrase')
            ->will($this->returnValue("OK"));

        $clientMock = $this->getMock("GuzzleClient", array("get", "send"));
        $clientMock->expects($this->any())
            ->method('get')
            ->will($this->returnValue($clientMock));
        $clientMock->expects($this->any())
            ->method('send')
            ->will($this->returnValue($responseMock));

        $request = array(
            "REQUEST_METHOD" => "GET",
            "SERVER_PROTOCOL" => "HTTP/1.1",
            "HTTP_HOST" => "eher.com.br",
            "PATH_INFO" => "/test/path",
        );
        $parameters = array(
            "eher" => "test",
        );
        $this->request = new Request($request, $parameters);
        $response = $this->request->execute($clientMock);
        $this->assertTrue($response instanceof Response);
        $this->assertEquals('200', $response->getStatusCode());
        $this->assertEquals('OK', $response->getBody());
    }

    public function testSaveRequest()
    {
        $expectedId = 'ec809ff076d31814dead7dfa54895485';
        $expectedJson = '{"verb":"get","protocol":"http","hostname":"eher.com.br","path":"\/test\/path","parameters":{"eher":"test"}}';

        $clientMock = $this->getMock("PredisClient", array("set"));
        $clientMock->expects($this->once())
            ->method('set')
            ->with(
                $this->equalTo($expectedId),
                $this->equalTo($expectedJson)
            );

        $request = array(
            "REQUEST_METHOD" => "GET",
            "SERVER_PROTOCOL" => "HTTP/1.1",
            "HTTP_HOST" => "eher.com.br",
            "PATH_INFO" => "/test/path",
        );
        $parameters = array(
            "eher" => "test",
        );
        $this->request = new Request($request, $parameters);
        $response = $this->request->save($clientMock);
    }
}
