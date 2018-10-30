<?php

namespace VirginMoneyGivingAPI\Responses;

use VirginMoneyGivingAPI\Models\ModelInterface;

interface CreateResponseInterface
{
    public function setModel(ModelInterface $model);

    public function getModel(): ModelInterface;

    public function isCreationSuccessful(): bool;

    public function setCreationSuccessful(bool $creationSuccessful);

    public function getMessage();

    public function setMessage($message);
}
