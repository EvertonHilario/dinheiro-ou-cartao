<?php

namespace App\Domain\Repositories;

interface ExternalAuthorizerRepositoryInterface
{
    /**
     * @return Bool
     */
    function check(): Bool;
}
