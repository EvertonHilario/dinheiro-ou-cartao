<?php

namespace App\Infrastructure\Api;

use App\Domain\Repositories\ExternalMessengerRepositoryInterface;
use Illuminate\Support\Facades\Http;

class ExternalMessengerRepository implements ExternalMessengerRepositoryInterface
{
    private $attemptsSending = 1;

    /**
     * @return Bool
     */
    public function send(array $attributes): Bool
    {
        $url = sprintf(
            "%s/%s/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04",
            getenv("EXT_MEN_BASE_URL"),
            getenv("EXT_MEN_VERSION"),
        );

        $response = Http::get($url);

        try {
            if (
                $response->failed() ||
                $response->clientError() ||
                $response->serverError() ||
                !$response->json()['message'] == "Enviado"
            ) {
                throw new \Exception('Mensageiro externo indisponível no momento.');
            }

            return true;
        } catch (\Exception $e) {
            if ($this->attemptsSending <= getenv('EXT_MEN_ATTEMPTS_SENDING')) {
                error_log(
                    "[error] Serviço externo => UserId:{$attributes['users_id']}"
                        . " Tentativas de envio:{$this->attemptsSending}\n",
                    3,
                    getenv('LOGS_MESSENGER')
                );
                $this->attemptsSending++;
                $this->send($attributes);
            }
        }
        error_log(
            "[error] Serviço externo => Forma feitas "
                . getenv('EXT_MEN_ATTEMPTS_SENDING') . " tentativas de envio sem sucesso\n",
            3,
            getenv('LOGS_MESSENGER')
        );
        throw new \Exception('Mensageiro externo indisponível no momento.');
    }
}
