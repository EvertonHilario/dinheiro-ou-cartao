<?php

namespace App\Domain\Repositories;

interface ExternalMessengerRepositoryInterface
{
    /**
     * @return Bool
     */
    function send(array $attributes): Bool;
}
