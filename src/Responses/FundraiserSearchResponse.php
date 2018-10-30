<?php

namespace VirginMoneyGivingAPI\Responses;

use Psr\Http\Message\ResponseInterface;

class FundraiserSearchResponse
{
    /**
     * @var array
     */
    protected $results;

    /**
     * @var string
     */
    protected $message;

    public function __construct(ResponseInterface $response)
    {
        $body = \GuzzleHttp\json_decode($response->getBody());

        $this->setMessage($body->message);
        $this->setResults($body->fundraiserResults);
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @param array $results
     *
     * @return FundraiserSearchResponse
     */
    public function setResults(array $results): self
    {
        $this->results = $results;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return FundraiserSearchResponse
     */
    public function setMessage($message): self
    {
        $this->message = ($message) ? $message : null;

        return $this;
    }

    public function hasMatches() : bool
    {
        return !empty($this->getResults());
    }
}
