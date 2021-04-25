<?php

namespace App\Jobs;

use App\Domain\Services\Messenger;
use App\Domain\Models\TransactionsNotification;
use App\Domain\Repositories\ExternalMessengerRepositoryInterface;

class MessengerJob extends Job
{
    private $attributes;
    private $notification;
    private $messenger;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        TransactionsNotification $notification,
        array $attributes,
        ExternalMessengerRepositoryInterface $messenger
    ) {
        $this->attributes = $attributes;
        $this->notification = $notification;
        $this->messenger = $messenger;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return Messenger::pull($this->notification, $this->attributes, $this->messenger);
    }
}
