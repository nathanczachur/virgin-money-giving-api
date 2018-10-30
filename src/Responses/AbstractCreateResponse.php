<?php

namespace VirginMoneyGivingAPI\Responses;

use VirginMoneyGivingAPI\Models\ModelInterface;

/**
 * Bundles together the shared stuff that all 'create' requests do.
 *
 * Class AbstractCreateResponse
 */
abstract class AbstractCreateResponse implements CreateResponseInterface
{
    /**
     * @var \VirginMoneyGivingAPI\Models\ModelInterface
     */
    protected $model;

    /**
     * @var bool
     */
    protected $creationSuccessful;

    /**
     * @var string
     */
    protected $message;

    /**
     * @return \VirginMoneyGivingAPI\Models\ModelInterface
     */
    public function getModel(): ModelInterface
    {
        return $this->model;
    }

    /**
     * @param \VirginMoneyGivingAPI\Models\ModelInterface $fundraiser
     */
    public function setModel(ModelInterface $fundraiser)
    {
        $this->model = $fundraiser;
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
     */
    public function setMessage($message)
    {
        $this->message = ($message) ? $message : null;
    }

    /**
     * @return bool
     */
    public function isCreationSuccessful(): bool
    {
        return $this->creationSuccessful;
    }

    /**
     * @param bool $creationSuccessful
     */
    public function setCreationSuccessful(bool $creationSuccessful)
    {
        $this->creationSuccessful = $creationSuccessful;
    }
}
