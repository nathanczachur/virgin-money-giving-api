<?php

namespace VirginMoneyGivingAPI;

use GuzzleHttp\Client;

interface VmgConnectorInterface
{
    public function setApiKey(string $apiKey);

    public function getApiKey();

    public function setTestMode($value);

    public function getTestMode();

    public function setGuzzleClient(Client $client);

    public function getGuzzleClient();

    public function request($path, $method = 'GET', $data = []);
}
