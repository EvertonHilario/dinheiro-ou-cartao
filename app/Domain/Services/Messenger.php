<?php

namespace App\Domain\Services;

use App\Domain\Models\TransactionsNotification;
use App\Domain\Repositories\TransactionsNotificationRepositoryInterface;
use App\Domain\Repositories\ExternalMessengerRepositoryInterface;
use App\Jobs\MessengerJob;

class Messenger
{
    private $notification;
    private $messenger;

    const STATUS_SENDING = 1;
    const STATUS_SENT = 2;
    const STATUS_ERROR = 3;

    public function __construct(
        TransactionsNotificationRepositoryInterface $notification,
        ExternalMessengerRepositoryInterface $messenger
    ) {
        $this->notification = $notification;
        $this->messenger = $messenger;
    }

    public function push(array $attributes): void
    {
        //salva na base o envio da mensagem: status = enviando
        $attributes['status'] = self::STATUS_SENDING;
        $notification = $this->notification->create($attributes);

        //envia a mensagem para fila do rabbitmk
        $this->dispatch($notification, $attributes, $this->messenger);
        error_log("MESSENGER => push: UserId-> {$attributes['users_id']}\n", 3, "/var/www/html/storage/logs/messages.log");
    }

    public static function pull(
        TransactionsNotification $notification,
        array $attributes,
        ExternalMessengerRepositoryInterface $messenger
    ): void {
        // envia a mensagem pelo mensageiro externo
        $messenger->send($attributes);

        // atualiza o status da mensagem: status = enviado
        $notification->status = self::STATUS_SENT;
        $notification->update();
        error_log("MESSENGER => pull: UserId-> {$attributes['users_id']}\n", 3, "/var/www/html/storage/logs/messages.log");
    }

    private function dispatch(
        TransactionsNotification $notification,
        array $attributes,
        ExternalMessengerRepositoryInterface $messenger
    ): void {
        $job = new MessengerJob($notification, $attributes, $messenger);

        if (getenv('APP_ENV') == 'local') {
            $job->delay(\Carbon\Carbon::now()->addSeconds(5));
        }

        dispatch($job);
    }
}
