<?php

namespace App\Infrastructure\Api;

use App\Domain\Repositories\ExternalAuthorizerRepositoryInterface;
use Illuminate\Support\Facades\Http;

class ExternalAuthorizerRepository implements ExternalAuthorizerRepositoryInterface
{
    /**
     * @return Bool
     */
    public function check(): Bool
    {
        $url = sprintf(
            "%s/%s/8fafdd68-a090-496f-8c9a-3442cf30dae6",
            getenv("EXT_AUT_BASE_URL"),
            getenv("EXT_AUT_VERSION"),
        );

        $response = Http::get($url);

        if ($response->failed() || $response->clientError() || $response->serverError()) {
            throw new \Exception ('Autenticador externo indisponível no momento.');
        }

        if(!$response->json()['message'] == "Autorizado") {
            return false;
        }

        return true;
    }
}
