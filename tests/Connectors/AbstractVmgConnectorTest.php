<?php

namespace Tests\Connectors;

use Tests\Connectors\ConcreteConnector as VmgConnector;
use Tests\VmgTestBase;
use VirginMoneyGivingAPI\Exceptions\ConnectorException;
use GuzzleHttp\Psr7\Response;

class AbstractVmgConnectorTest extends VmgTestBase
{
    public function testEndpointUrl()
    {
        $connector = new VmgConnector('API_KEY', $this->getGuzzleClient(), $testMode = false);

        // Check we are looking at the live endpoint.
        $this->assertSame('https://api.virginmoneygiving.com', $connector->getEndpoint());

        // And now the sandbox when in test mode.
        $connector->setTestMode(true);
        $this->assertSame('https://sandbox.api.virginmoneygiving.com', $connector->getEndpoint());
    }

    public function testRequestMethod()
    {
        $connector = new VmgConnector('API_KEY', $this->getGuzzleClient(), $testMode = true);

        $this->expectExceptionMessage('Only POST and GET requests are supported.');
        $this->expectException(ConnectorException::class);
        $response = $connector->request('http://example.com', 'NOT VALID');
    }

    public function testUrlRejected()
    {
        $stream = file_get_contents('tests/Mocks/URlRejected.txt');
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        $this->setMockClient([$response]);

        $connector = new VmgConnector('API_KEY', $this->getGuzzleClient(), $testMode = true);

        $this->expectExceptionMessage('URL rejected by VMG. This is usually because the request has something the API cannot process such as accented characters. See the support ID in the messageDetails.');
        $this->expectException(ConnectorException::class);
        $response = $connector->request('http://example.com');
    }

    // @todo - Tests for 404 and 403 repsonses.
}
