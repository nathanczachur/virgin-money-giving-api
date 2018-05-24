<?php

namespace VirginMoneyGivingAPI;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use VirginMoneyGivingAPI\Exceptions\ConnectorException;
use function GuzzleHttp\json_decode;

abstract class AbstractVmgConnector implements VmgConnectorInterface
{
    /**
     * Sandbox Endpoint URL
     *
     * The VMG API has a sandbox environment in which to test. Contact your
     * account manager to get test credentials or use the ones in @todo.
     *
     * @var string URL
     */
    protected $testEndpoint = 'https://sandbox.api.virginmoneygiving.com';

    /**
     * @var string URL
     */
    protected $liveEndpoint = 'https://api.virginmoneygiving.com';

    /**
     * @var string The VMG API key
     */
    protected $apiKey;

    /**
     * @var \GuzzleHttp\Client The Guzzle client
     */
    protected $guzzleClient;

    /**
     * @var bool
     */
    private $testMode;

    /**
     * @var json
     */
    protected $errors;

    public function __construct($apiKey, Client $client, $testMode = false)
    {
        $this->setApiKey($apiKey);
        $this->setGuzzleClient($client);
        $this->setTestMode($testMode);
    }

    /**
     * Setter for the API key.
     *
     * @param string $apiKey
     *
     * @return $this
     */
    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getApiKey() : string
    {
        return $this->apiKey;
    }

    /**
     * Sets the test mode.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setTestMode($value)
    {
        $this->testMode = $value;

        return $this;
    }

    /**
     * Gets the test mode.
     *
     * @return boolean
     */
    public function getTestMode() : bool
    {
        return $this->testMode;
    }

    /**
     * Setter for the Guzzle client.
     *
     * @param \GuzzleHttp\ClientInterface $client
     *
     * @return $this
     */
    public function setGuzzleClient(Client $client)
    {
        $this->guzzleClient = $client;

        return $this;
    }

    public function getGuzzleClient() : Client
    {
        return $this->guzzleClient;
    }

    /**
     * Returns the endpoint based on if we are in test mode or not.
     *
     * @return string
     */
    public function getEndpoint() : string
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * Make a call to the VMG API.
     *
     * @param $path
     * @param string $method
     * @param array $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \VirginMoneyGivingAPI\Exceptions\ConnectorException
     */
    public function request($path, $method = 'GET', $options = [])
    {
        // Make sure we are posting or getting.
        if ($method != 'POST' && $method != 'GET') {
            throw new ConnectorException('Only POST and GET requests are supported.');
        }

        // Set up the request URL based on the request path.
        $url = $this->getEndpoint() . $path . '&api_key=' . $this->getApiKey();

        // Give the request a go and catch the errors we want to handle.
        try {
            // We need to switch on get and post
            $client = $this->getGuzzleClient();
            if ($method == 'GET') {
                $response = $client->get($url, $options);
            } else {
                $response = $client->post($url, $options);
            }
        } catch (RequestException $exception) {
            // Give a specific response message depending on the response code.
            switch ($exception->getCode()) {
                case 404:
                    $message = 'VMG has returned a 404. This can mean the URL isn\'t found or that a lookup has returned no results.';
                    break;
                case 403:
                    $message = 'VMG has returned a 403. This usually means your API key is either invalid or you\'re trying to use it against the wrong API. Remember fundraiser API keys cannot be used to create a fundraiser account for example. This can also mean your request isn\'t in the right format. If you\'re constantly getting this get in touch with VMG.';
                    break;
                default:
                    $message = 'VMG has responded with an error. Please check the exception.';
                    break;

            }

            var_dump($exception->getResponse());

            throw new ConnectorException($message, $exception->getCode(), $exception->getPrevious(), $exception->getResponse()->getBody()->getContents());
        }

        // @todo - We can get a 200 that is '<html><head><title>Request Rejected</title>' so we need to try and figure out how to do that too

        // Send the response back for whoever called this to deal with.
        return $response;
    }
}