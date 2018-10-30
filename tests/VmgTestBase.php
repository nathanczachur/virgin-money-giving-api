<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class VmgTestBase extends TestCase
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzleClient;

    /**
     * @var string
     */
    public $mockFilePath = 'tests/Mocks/';

    public function setUp()
    {
        $this->setGuzzleClient(new Client());
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getGuzzleClient(): \GuzzleHttp\Client
    {
        return $this->guzzleClient;
    }

    /**
     * @param \GuzzleHttp\Client $guzzleClient
     */
    public function setGuzzleClient(\GuzzleHttp\Client $guzzleClient): void
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * Given a mocked response, set the Guzzle client to use it.
     *
     * @param array
     *
     * @return $this
     */
    public function setMockClient($responses)
    {
        $mock = new MockHandler($responses);

        $handler = HandlerStack::create($mock);

        $this->setGuzzleClient(new Client(['handler' => $handler]));

        return $this;
    }
}
