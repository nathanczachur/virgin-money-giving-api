<?php

namespace VirginMoneyGivingAPI\Responses;

use Psr\Http\Message\ResponseInterface;
use VirginMoneyGivingAPI\Models\Fundraiser;

class FundraiserCreateResponse extends AbstractCreateResponse
{
    //\VirginMoneyGivingAPI\Models\ModelInterface?
    protected $fundraiser;

    /**
     * @var bool
     */
    protected $creationSuccessful;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var string
     */
    protected $personalUrl;

    /**
     * @var bool
     */
    protected $customerExists;

    public function __construct(ResponseInterface $response, Fundraiser $fundraiser)
    {
        $body = \GuzzleHttp\json_decode($response->getBody());

        $fundraiser->setResourceId($body->resourceId);
        $this->setModel($fundraiser);
        $this->setCreationSuccessful($body->creationSuccessful);
        $this->setAccessToken($body->accessToken);
        $this->setCustomerExists($body->customerExists);
        $this->setMessage($body->message);
        $this->setPersonalUrl($body->personalUrl);
    }

    /**
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     *
     * @return FundraiserCreateResponse
     */
    public function setAccessToken($accessToken): self
    {
        $this->accessToken = ($accessToken) ? $accessToken : null;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCustomerExists(): bool
    {
        return $this->customerExists;
    }

    /**
     * @param bool $customerExists
     *
     * @return FundraiserCreateResponse
     */
    public function setCustomerExists(bool $customerExists): self
    {
        $this->customerExists = $customerExists;

        return $this;
    }

    /**
     * @return string
     */
    public function getPersonalUrl(): string
    {
        return $this->personalUrl;
    }

    /**
     * @param string $personalUrl
     *
     * @return FundraiserCreateResponse
     */
    public function setPersonalUrl(string $personalUrl): self
    {
        $this->personalUrl = $personalUrl;

        return $this;
    }
}
