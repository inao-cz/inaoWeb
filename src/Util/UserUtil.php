<?php

namespace App\Util;

use Exception;
use Symfony\Component\Uid\Uuid;

class UserUtil
{
    /**
     * @throws Exception
     */
    public function getRandomApiKey(): string
    {
        return Uuid::v6()->toRfc4122();
    }
}