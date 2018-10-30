<?php

namespace VirginMoneyGivingAPI\Models;

interface ModelInterface
{
    public function setResourceId(string $resourceID);

    public function getResourceId();

    public function convertAccentedCharacters(string $string);
}
