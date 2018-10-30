<?php

namespace VirginMoneyGivingAPI\Models;

use function Rap2hpoutre\ConvertAccentCharacters\convert_accent_characters;

/**
 * Shared functionality across all models.
 *
 * @todo - It looks like we don't get a resource ID back from the
 * page create API. So this needs refactoring.
 *
 * Class AbstractModel
 */
abstract class AbstractModel implements ModelInterface
{
    /**
     * @var string
     */
    protected $resourceID;

    public function setResourceId(string $resourceID): self
    {
        $this->resourceID = $resourceID;

        return $this;
    }

    public function getResourceId()
    {
        return $this->resourceID;
    }

    public function convertAccentedCharacters(string $string)
    {
        return convert_accent_characters($string);
    }
}
