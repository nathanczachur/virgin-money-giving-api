<?php

namespace VirginMoneyGivingAPI\Responses;

use Psr\Http\Message\ResponseInterface;
use VirginMoneyGivingAPI\Models\Page;

class PageCreateResponse extends AbstractCreateResponse
{
    /**
     * @var string
     */
    protected $pageURI;

    public function __construct(ResponseInterface $response, Page $page)
    {
        $body = \GuzzleHttp\json_decode($response->getBody());

        $this->setModel($page);
        $this->setCreationSuccessful($body->creationSuccessful);
        $this->setMessage($body->message);
        $this->setPageURI($body->pageURI);
    }

    /**
     * @return string
     */
    public function getPageURI(): string
    {
        return $this->pageURI;
    }

    /**
     * @param string $pageURI
     *
     * @return PageCreateResponse
     */
    public function setPageURI(string $pageURI): self
    {
        $this->pageURI = $pageURI;

        return $this;
    }
}
